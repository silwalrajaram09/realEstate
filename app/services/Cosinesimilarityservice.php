<?php

namespace App\Services;

use Illuminate\Support\Collection;

class CosineSimilarityService
{
    private const TEXT_FIELD_WEIGHTS = [
        'location' => 4.0,
        'title' => 2.5,
        'type' => 2.0,
        'purpose' => 1.5,
        'category' => 1.5,
        'description' => 1.0,
    ];

    private const STOPWORDS = [
        'a', 'an', 'the', 'and', 'or', 'for', 'in', 'on', 'at', 'to',
        'of', 'is', 'with', 'near', 'by', 'from', 'this', 'that', 'are',
        'it', 'its', 'be', 'has', 'have', 'was', 'as', 'up', 'but',
    ];

    private const VECTOR_DIM = 13;

    public function rerank(Collection $properties, string $query): Collection
    {
        if ($properties->isEmpty() || trim($query) === '') {
            return $properties;
        }

        $queryRawTf = $this->termFrequency($this->tokenize($query));
        $indexed = $properties->values();
        $corpus = $indexed->map(fn ($p) => $this->buildTextDocument($p))->all();
        $idf = $this->computeIdf($corpus);
        $queryVec = $this->applyIdf($queryRawTf, $idf);

        return $indexed
            ->map(function ($property, $idx) use ($corpus, $idf, $queryVec) {
                $docVec = $this->applyIdf($corpus[$idx] ?? [], $idf);
                $property->cosine_score = $this->cosineText($queryVec, $docVec);
                $property->similarity_score = $property->cosine_score;
                return $property;
            })
            ->sortByDesc('cosine_score')
            ->values();
    }

    public function score(object $property, string $query): float
    {
        if (trim($query) === '') {
            return 0.0;
        }

        $queryVec = $this->termFrequency($this->tokenize($query));
        $docVec = $this->buildTextDocument($property);

        return $this->cosineText($queryVec, $docVec);
    }

    public function rankForUser(Collection $favorites, Collection $views, Collection $candidates, int $limit = 12): Collection
    {
        $userVector = $this->buildUserVector($favorites, $views);

        if ($userVector === null) {
            return $candidates->take($limit)->each(fn ($p) => $p->similarity_score = null);
        }

        return $candidates
            ->map(function ($property) use ($userVector) {
                $propVector = $this->buildPropertyVector($property);
                $property->cosine_score = $this->cosineNumeric($userVector, $propVector);
                $property->similarity_score = $property->cosine_score;
                return $property;
            })
            ->sortByDesc('cosine_score')
            ->take($limit)
            ->values();
    }

    public function buildUserVector(Collection $favorites, Collection $views): ?array
    {
        $vectors = [];

        foreach ($favorites as $property) {
            $vec = $this->buildPropertyVector($property);
            $vectors[] = $vec;
            $vectors[] = $vec;
            $vectors[] = $vec;
        }

        foreach ($views as $property) {
            $vectors[] = $this->buildPropertyVector($property);
        }

        if (empty($vectors)) {
            return null;
        }

        return $this->averageVectors($vectors);
    }

    public function buildPropertyVector(object $property): array
    {
        return [
            min((float) ($property->price ?? 0) / 100000000, 5.0),
            min((float) ($property->bedrooms ?? 0) / 10, 1.0),
            min((float) ($property->bathrooms ?? 0) / 10, 1.0),
            min((float) ($property->area ?? 0) / 10000, 1.0),
            ($property->purpose ?? '') === 'buy' ? 1.0 : 0.0,
            ($property->purpose ?? '') === 'rent' ? 1.0 : 0.0,
            ($property->type ?? '') === 'house' ? 1.0 : 0.0,
            ($property->type ?? '') === 'flat' ? 1.0 : 0.0,
            ($property->type ?? '') === 'land' ? 1.0 : 0.0,
            ($property->type ?? '') === 'commercial' ? 1.0 : 0.0,
            ($property->category ?? '') === 'residential' ? 1.0 : 0.0,
            ($property->category ?? '') === 'commercial' ? 1.0 : 0.0,
            ($property->category ?? '') === 'industrial' ? 1.0 : 0.0,
        ];
    }

    private function buildTextDocument(object $property): array
    {
        $vector = [];

        foreach (self::TEXT_FIELD_WEIGHTS as $field => $weight) {
            $text = (string) ($property->{$field} ?? '');
            $tf = $this->termFrequency($this->tokenize($text));

            foreach ($tf as $term => $freq) {
                $vector[$term] = ($vector[$term] ?? 0.0) + ($freq * $weight);
            }
        }

        return $vector;
    }

    private function computeIdf(array $corpus): array
    {
        $N = count($corpus);
        $df = [];

        foreach ($corpus as $vector) {
            foreach (array_keys($vector) as $term) {
                $df[$term] = ($df[$term] ?? 0) + 1;
            }
        }

        $idf = [];
        foreach ($df as $term => $count) {
            $idf[$term] = log(($N + 1) / ($count + 1)) + 1;
        }

        return $idf;
    }

    private function applyIdf(array $tf, array $idf): array
    {
        $result = [];
        foreach ($tf as $term => $freq) {
            $result[$term] = $freq * ($idf[$term] ?? 1.0);
        }
        return $result;
    }

    private function cosineText(array $a, array $b): float
    {
        if (empty($a) || empty($b)) {
            return 0.0;
        }

        $dot = 0.0;
        foreach ($a as $term => $weight) {
            if (isset($b[$term])) {
                $dot += $weight * $b[$term];
            }
        }

        if ($dot === 0.0) {
            return 0.0;
        }

        $magA = sqrt(array_sum(array_map(fn ($v) => $v ** 2, $a)));
        $magB = sqrt(array_sum(array_map(fn ($v) => $v ** 2, $b)));

        if ($magA === 0.0 || $magB === 0.0) {
            return 0.0;
        }

        return round($dot / ($magA * $magB), 6);
    }

    private function cosineNumeric(array $a, array $b): float
    {
        $dot = 0.0;
        $magA = 0.0;
        $magB = 0.0;
        $len = min(count($a), count($b));

        for ($i = 0; $i < $len; $i++) {
            $dot += $a[$i] * $b[$i];
            $magA += $a[$i] ** 2;
            $magB += $b[$i] ** 2;
        }

        if ($magA === 0.0 || $magB === 0.0) {
            return 0.0;
        }

        return round($dot / (sqrt($magA) * sqrt($magB)), 6);
    }

    private function averageVectors(array $vectors): array
    {
        $count = count($vectors);
        $result = array_fill(0, self::VECTOR_DIM, 0.0);

        foreach ($vectors as $vec) {
            foreach ($vec as $i => $val) {
                $result[$i] += $val;
            }
        }

        return array_map(fn ($v) => $v / $count, $result);
    }

    private function tokenize(string $text): array
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s]/u', ' ', $text) ?? '';
        $tokens = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $tokens = array_map(function (string $token): string {
            foreach (['ings', 'ing', 'tion', 'tions', 'ness', 'ies', 'es', 'ed', 's'] as $suffix) {
                if (strlen($token) > strlen($suffix) + 3 && str_ends_with($token, $suffix)) {
                    return substr($token, 0, strlen($token) - strlen($suffix));
                }
            }
            return $token;
        }, $tokens);

        return array_values(array_filter($tokens, fn ($t) => !in_array($t, self::STOPWORDS, true) && strlen($t) > 1));
    }

    private function termFrequency(array $tokens): array
    {
        if (empty($tokens)) {
            return [];
        }

        $counts = array_count_values($tokens);
        $total = count($tokens);

        return array_map(fn ($c) => $c / $total, $counts);
    }
}
