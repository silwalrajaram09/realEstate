<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Property;
use App\Models\PropertyVector;
use App\Models\PropertyView;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PropertyRecommendationService
{
    private const RECENT_DAYS_WINDOW = 30;
    private const MIN_SIMILAR_COSINE = 0.20;
    private const MIN_PERSONALIZED_COSINE = 0.12;
    private const CANDIDATE_POOL_LIMIT = 400;

    private const FEATURE_KEYS = [
        'price',
        'area',
        'bedrooms',
        'bathrooms',
        'type',
        'category',
        'purpose',
        'latitude',
        'longitude',
    ];

    public function search(array $filters, int $perPage = 6): LengthAwarePaginator
    {
        $query = Property::query()->approved()->with('seller')->orderByDesc('is_featured')->latest();
        if (!empty($filters['purpose'])) $query->where('purpose', $filters['purpose']);
        if (!empty($filters['type'])) $query->where('type', $filters['type']);
        if (!empty($filters['category'])) $query->where('category', $filters['category']);
        if (!empty($filters['min_price'])) $query->where('price', '>=', (float) $filters['min_price']);
        if (!empty($filters['max_price'])) $query->where('price', '<=', (float) $filters['max_price']);
        if (!empty($filters['min_area'])) $query->where('area', '>=', (float) $filters['min_area']);
        if (!empty($filters['max_area'])) $query->where('area', '<=', (float) $filters['max_area']);
        if (!empty($filters['bedrooms'])) $query->where('bedrooms', '>=', (int) $filters['bedrooms']);
        if (!empty($filters['bathrooms'])) $query->where('bathrooms', '>=', (int) $filters['bathrooms']);
        if (!empty($filters['lat']) && !empty($filters['lng']) && !empty($filters['radius'])) {
            $radiusKm = (float) $filters['radius'];
            $query->select('properties.*')
                ->selectRaw(
                    '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                    [$filters['lat'], $filters['lng'], $filters['lat']]
                )
                ->having('distance', '<=', $radiusKm)
                ->orderBy('distance');
        }
        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }
        return $query->paginate(min(max($perPage, 6), 48))->withQueryString();
    }

    public function refreshPropertyVectors(?array $propertyIds = null): int
    {
        $propertiesQuery = Property::query()->approved();
        if (!empty($propertyIds)) {
            $propertiesQuery->whereIn('id', $propertyIds);
        }
        $properties = $propertiesQuery->get();
        if ($properties->isEmpty()) {
            return 0;
        }
        $rawVectors = $properties->mapWithKeys(function (Property $property) {
            return [$property->id => $this->buildRawVector($property)];
        });
        $bounds = $this->computeBounds($rawVectors->values()->all());

        $rows = [];
        foreach ($properties as $property) {
            $raw = $rawVectors[$property->id];
            $rows[] = [
                'property_id' => $property->id,
                'vector' => json_encode($this->normalizeVector($raw, $bounds)),
                'raw_vector' => json_encode($raw),
                'normalized_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        PropertyVector::query()->upsert($rows, ['property_id'], ['vector', 'raw_vector', 'normalized_at', 'updated_at']);
        return count($rows);
    }

    public function refreshChangedPropertyVectors(int $sinceHours = 24): int
    {
        $ids = Property::query()
            ->approved()
            ->where('updated_at', '>=', now()->subHours(max(1, $sinceHours)))
            ->pluck('id')
            ->all();

        if (empty($ids)) {
            return 0;
        }

        return $this->refreshPropertyVectors($ids);
    }

    public function getSimilarProperties(Property $property, int $limit = 6, ?int $buyerId = null): Collection
    {
        $sourceVector = $this->getVectorByPropertyId($property->id);
        if (!$sourceVector) {
            return collect();
        }
        $excludeIds = collect([$property->id]);
        if ($buyerId) {
            $excludeIds = $excludeIds
                ->merge($this->seenPropertyIds($buyerId))
                ->merge($this->savedPropertyIds($buyerId))
                ->unique()
                ->values();
        }
        return $this->rankByVector($sourceVector, $excludeIds->all(), $limit, [
            'mode' => 'similar',
            'reference_property' => $property,
        ]);
    }

    public function personalized(array $preferences, int $limit = 6, ?int $userId = null): Collection
    {
        if (!$userId) {
            return $this->exploreFeed($limit);
        }

        $viewIds = PropertyView::query()
            ->where(function ($q) use ($userId) {
                $q->where('buyer_id', $userId)->orWhere('user_id', $userId);
            })
            ->orderByDesc('viewed_at')
            ->limit(10)
            ->pluck('property_id');

        $historyVectors = PropertyVector::query()
            ->whereIn('property_id', $viewIds)
            ->get()
            ->map(fn(PropertyVector $v) => $v->vector_array)
            ->filter();

        if ($historyVectors->isEmpty()) {
            return $this->exploreFeed($limit);
        }

        $buyerVector = $this->averageVectors($historyVectors->values()->all());
        $exclude = $this->seenPropertyIds($userId)->merge($this->savedPropertyIds($userId))->unique()->values()->all();
        return $this->rankByVector($buyerVector, $exclude, $limit, [
            'mode' => 'personalized',
            'preferences' => $preferences,
        ]);
    }

    public function weeklyDigestRecommendations(int $buyerId, int $limit = 6): Collection
    {
        return $this->personalized([], $limit, $buyerId);
    }

    public function withinRadius(float $lat, float $lng, int $radius = 10): Collection
    {
        $radiusKm = max(1, $radius);
        return Property::query()
            ->approved()
            ->select('properties.*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance')
            ->get();
    }

    public function stats(): array
    {
        return [
            'total' => Property::approved()->count(),
            'sale' => Property::approved()->where('purpose', 'buy')->count(),
            'rent' => Property::approved()->where('purpose', 'rent')->count(),
            'avg' => Property::approved()->avg('price'),
        ];
    }

    private function rankByVector(array $sourceVector, array $excludeIds, int $limit, array $context = []): Collection
    {
        $query = PropertyVector::query()
            ->with('property.seller')
            ->whereHas('property', fn($p) => $this->applyCandidateGuards($p, $context))
            ->limit(self::CANDIDATE_POOL_LIMIT);
        if (!empty($excludeIds)) {
            $query->whereNotIn('property_id', $excludeIds);
        }
        return $query->get()
            ->map(function (PropertyVector $candidate) use ($sourceVector, $context) {
                $property = $candidate->property;
                if (!$property) {
                    return null;
                }

                $cosine = $this->cosineSimilarity($sourceVector, $candidate->vector_array);
                $blended = $this->blendRankScore($property, $cosine, $context);
                $property->cosine_score = round($cosine, 4);
                $property->similarity_score = round($blended, 4);
                $property->recommendation_confidence = $this->confidenceLabel($property->similarity_score);
                $property->recommendation_reason = $this->buildReason($property, $context);
                return $property;
            })
            ->filter()
            ->filter(function (Property $property) use ($context) {
                if (($context['mode'] ?? null) !== 'similar') {
                    return (float) ($property->cosine_score ?? 0.0) >= self::MIN_PERSONALIZED_COSINE;
                }
                return $this->passesSimilarCriteria($property, $context);
            })
            ->unique('id')
            ->sortByDesc('similarity_score')
            ->take($limit)
            ->values();
    }

    private function passesSimilarCriteria(Property $property, array $context): bool
    {
        $ref = $context['reference_property'] ?? null;
        if (!$ref instanceof Property) {
            return true;
        }

        // Hard match: rental should recommend rental, buy should recommend buy.
        if ($property->purpose !== $ref->purpose) {
            return false;
        }

        // Minimum vector similarity required.
        if ((float) ($property->cosine_score ?? 0) < self::MIN_SIMILAR_COSINE) {
            return false;
        }

        // Score by additional matching criteria.
        $criteriaScore = 0;
        if ($property->type && $property->type === $ref->type) {
            $criteriaScore++;
        }
        if ($property->category && $property->category === $ref->category) {
            $criteriaScore++;
        }

        $refPrice = (float) ($ref->price ?? 0);
        $candidatePrice = (float) ($property->price ?? 0);
        if ($refPrice > 0 && $candidatePrice > 0) {
            $ratio = abs($candidatePrice - $refPrice) / $refPrice;
            if ($ratio <= 0.40) { // within +-40%
                $criteriaScore++;
            }
        }

        $refBeds = (int) ($ref->bedrooms ?? 0);
        $candidateBeds = (int) ($property->bedrooms ?? 0);
        if ($refBeds > 0 && $candidateBeds > 0 && abs($candidateBeds - $refBeds) <= 1) {
            $criteriaScore++;
        }

        // Require at least two additional criteria to pass.
        return $criteriaScore >= 2;
    }

    private function applyCandidateGuards($query, array $context): void
    {
        $query->approved();
        $mode = $context['mode'] ?? 'personalized';

        if ($mode === 'similar' && isset($context['reference_property']) && $context['reference_property'] instanceof Property) {
            $ref = $context['reference_property'];
            $query->where('purpose', $ref->purpose);

            if (!empty($ref->category)) {
                $query->where('category', $ref->category);
            }

            if (!empty($ref->price)) {
                $min = (float) $ref->price * 0.55;
                $max = (float) $ref->price * 1.60;
                $query->whereBetween('price', [$min, $max]);
            }

            if (!empty($ref->area)) {
                $minA = (float) $ref->area * 0.50;
                $maxA = (float) $ref->area * 1.70;
                $query->whereBetween('area', [$minA, $maxA]);
            }
        }

        if ($mode === 'personalized' && !empty($context['preferences'])) {
            $prefs = $context['preferences'];
            if (!empty($prefs['purpose'])) {
                $query->where('purpose', $prefs['purpose']);
            }
            if (!empty($prefs['category'])) {
                $query->where('category', $prefs['category']);
            }
            if (!empty($prefs['min_price']) && !empty($prefs['max_price'])) {
                $query->whereBetween('price', [(float) $prefs['min_price'], (float) $prefs['max_price']]);
            }
        }
    }

    private function blendRankScore(Property $property, float $cosine, array $context): float
    {
        $mode = $context['mode'] ?? 'personalized';
        $cosineWeight = $mode === 'similar' ? 0.75 : 0.65;
        $metaWeight = 1 - $cosineWeight;

        $metaScore = 0.0;

        // Boost featured inventory slightly.
        $metaScore += $property->is_featured ? 0.08 : 0.0;

        // Prefer fresher inventory for better feed quality.
        $daysOld = now()->diffInDays($property->created_at ?? now());
        $freshness = max(0.0, 1 - min($daysOld, self::RECENT_DAYS_WINDOW) / self::RECENT_DAYS_WINDOW);
        $metaScore += $freshness * 0.08;

        // Mild popularity signal to avoid sparse/low-quality tail items.
        $views = (int) ($property->views_count ?? 0);
        $popularity = min(1.0, log(1 + $views) / 8);
        $metaScore += $popularity * 0.09;

        if ($mode === 'similar' && isset($context['reference_property']) && $context['reference_property'] instanceof Property) {
            $ref = $context['reference_property'];
            $metaScore += ($property->purpose === $ref->purpose) ? 0.06 : 0.0;
            $metaScore += ($property->type === $ref->type) ? 0.06 : 0.0;
            $metaScore += ($property->category === $ref->category) ? 0.04 : 0.0;
        }

        if ($mode === 'personalized' && !empty($context['preferences'])) {
            $prefs = $context['preferences'];
            $metaScore += (($prefs['purpose'] ?? null) && $property->purpose === $prefs['purpose']) ? 0.05 : 0.0;
            $metaScore += (($prefs['type'] ?? null) && $property->type === $prefs['type']) ? 0.05 : 0.0;
            $metaScore += (($prefs['category'] ?? null) && $property->category === $prefs['category']) ? 0.03 : 0.0;
        }

        $metaScore = min($metaScore, 0.35);

        return ($cosine * $cosineWeight) + ($metaScore * $metaWeight);
    }

    private function exploreFeed(int $limit): Collection
    {
        return Property::query()
            ->approved()
            ->orderByDesc('is_featured')
            ->orderByDesc('views_count')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function (Property $property) {
                $property->cosine_score = null;
                $property->similarity_score = null;
                $property->recommendation_reason = 'Popular & featured listings right now';
                return $property;
            });
    }

    private function buildReason(Property $property, array $context): string
    {
        $mode = $context['mode'] ?? 'personalized';

        if ($mode === 'similar' && isset($context['reference_property']) && $context['reference_property'] instanceof Property) {
            $ref = $context['reference_property'];
            $bits = [];

            if ($property->type && $property->type === $ref->type) {
                $bits[] = ucfirst($property->type) . ' like this one';
            }

            if ($property->purpose && $property->purpose === $ref->purpose) {
                $bits[] = $property->purpose === 'rent' ? 'Similar rentals' : 'Similar homes for sale';
            }

            if ($property->category && $property->category === $ref->category) {
                $bits[] = $property->category . ' property';
            }

            return $bits
                ? implode(' · ', $bits)
                : 'Similar neighbourhood & price range';
        }

        if ($mode === 'personalized' && !empty($context['preferences'])) {
            $prefs = $context['preferences'];
            $parts = [];

            if (!empty($prefs['type'])) {
                $parts[] = ucfirst($prefs['type']);
            }
            if (!empty($prefs['bedrooms'])) {
                $parts[] = $prefs['bedrooms'] . ' BHK';
            }
            if (!empty($prefs['purpose'])) {
                $parts[] = $prefs['purpose'] === 'rent' ? 'for rent' : 'for sale';
            }

            if ($parts) {
                return 'Because you viewed similar ' . implode(' ', $parts);
            }

            return 'Matched to your recent activity';
        }

        return 'Recommended based on activity & popularity';
    }

    private function confidenceLabel(float $score): string
    {
        if ($score >= 0.75) {
            return 'high';
        }
        if ($score >= 0.45) {
            return 'medium';
        }
        return 'low';
    }

    private function buildRawVector(Property $property): array
    {
        $price = max(0.0, (float) ($property->price ?? 0));
        $area = max(0.0, (float) ($property->area ?? 0));
        $lat = (float) ($property->latitude ?? 0);
        $lng = (float) ($property->longitude ?? 0);

        return [
            // Log/sqrt transforms reduce outlier dominance and make cosine more stable.
            'price' => log(1 + $price),
            'area' => sqrt($area),
            'bedrooms' => min(10, max(0, (int) ($property->bedrooms ?? 0))),
            'bathrooms' => min(10, max(0, (int) ($property->bathrooms ?? 0))),
            'type' => (float) $this->encodeType((string) $property->type),
            'category' => (float) $this->encodeCategory((string) $property->category),
            'purpose' => (float) $this->encodePurpose((string) $property->purpose),
            'latitude' => $lat,
            'longitude' => $lng,
        ];
    }

    private function computeBounds(array $rawVectors): array
    {
        $bounds = [];
        foreach (self::FEATURE_KEYS as $key) {
            $values = array_map(fn($v) => $v[$key] ?? 0.0, $rawVectors);
            $bounds[$key] = [
                'min' => min($values),
                'max' => max($values),
            ];
        }
        return $bounds;
    }

    private function normalizeVector(array $raw, array $bounds): array
    {
        $normalized = [];
        foreach (self::FEATURE_KEYS as $key) {
            $min = $bounds[$key]['min'];
            $max = $bounds[$key]['max'];
            $value = $raw[$key] ?? 0.0;
            $normalized[$key] = ($max - $min) == 0.0 ? 0.0 : ($value - $min) / ($max - $min);
        }
        return $normalized;
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0;
        $magA = 0.0;
        $magB = 0.0;
        foreach (self::FEATURE_KEYS as $key) {
            $av = (float) ($a[$key] ?? 0.0);
            $bv = (float) ($b[$key] ?? 0.0);
            $dot += $av * $bv;
            $magA += $av ** 2;
            $magB += $bv ** 2;
        }
        if ($magA == 0.0 || $magB == 0.0) {
            return 0.0;
        }
        return $dot / (sqrt($magA) * sqrt($magB));
    }

    private function averageVectors(array $vectors): array
    {
        $count = count($vectors);
        $sum = array_fill_keys(self::FEATURE_KEYS, 0.0);
        foreach ($vectors as $vector) {
            foreach (self::FEATURE_KEYS as $key) {
                $sum[$key] += (float) ($vector[$key] ?? 0.0);
            }
        }
        foreach (self::FEATURE_KEYS as $key) {
            $sum[$key] = $count ? $sum[$key] / $count : 0.0;
        }
        return $sum;
    }

    private function getVectorByPropertyId(int $propertyId): ?array
    {
        $vector = PropertyVector::query()->where('property_id', $propertyId)->first();
        return $vector?->vector_array;
    }

    private function seenPropertyIds(int $buyerId): Collection
    {
        return PropertyView::query()
            ->where(function ($q) use ($buyerId) {
                $q->where('buyer_id', $buyerId)->orWhere('user_id', $buyerId);
            })
            ->pluck('property_id');
    }

    private function savedPropertyIds(int $buyerId): Collection
    {
        return Favorite::query()->where('user_id', $buyerId)->pluck('property_id');
    }

    public function diagnostics(int $sampleUsers = 30, int $limitPerUser = 6): array
    {
        $buyerIds = PropertyView::query()
            ->selectRaw('COALESCE(buyer_id, user_id) as uid')
            ->where(function ($q) {
                $q->whereNotNull('buyer_id')->orWhereNotNull('user_id');
            })
            ->groupBy('uid')
            ->orderByDesc(DB::raw('MAX(viewed_at)'))
            ->limit(max(1, $sampleUsers))
            ->pluck('uid')
            ->filter()
            ->map(fn($id) => (int) $id)
            ->values();

        if ($buyerIds->isEmpty()) {
            return [
                'sample_users' => 0,
                'avg_similarity' => 0,
                'avg_unique_categories' => 0,
                'repeat_rate' => 0,
                'coverage_rate' => 0,
            ];
        }

        $allRecommended = collect();
        $avgSimilarity = 0.0;
        $avgDiversity = 0.0;

        foreach ($buyerIds as $buyerId) {
            $prefs = [];
            $recs = $this->personalized($prefs, $limitPerUser, $buyerId);
            $allRecommended = $allRecommended->merge($recs->pluck('id'));
            $avgSimilarity += $recs->avg(fn($p) => (float) ($p->similarity_score ?? 0)) ?? 0.0;
            $avgDiversity += $recs->pluck('category')->filter()->unique()->count();
        }

        $totalReturned = $allRecommended->count();
        $uniqueReturned = $allRecommended->unique()->count();
        $approvedTotal = Property::approved()->count();
        $userCount = $buyerIds->count();

        return [
            'sample_users' => $userCount,
            'avg_similarity' => round($avgSimilarity / $userCount, 4),
            'avg_unique_categories' => round($avgDiversity / $userCount, 2),
            'repeat_rate' => $totalReturned ? round(1 - ($uniqueReturned / $totalReturned), 4) : 0,
            'coverage_rate' => $approvedTotal ? round($uniqueReturned / $approvedTotal, 4) : 0,
        ];
    }

    private function encodeType(string $type): int
    {
        return [
            'flat' => 1, 'house' => 2, 'land' => 3, 'commercial' => 4, 'office' => 5, 'warehouse' => 6,
        ][$type] ?? 0;
    }

    private function encodeCategory(string $category): int
    {
        return ['residential' => 1, 'commercial' => 2, 'industrial' => 3][$category] ?? 0;
    }

    private function encodePurpose(string $purpose): int
    {
        return ['buy' => 1, 'rent' => 2][$purpose] ?? 0;
    }
}