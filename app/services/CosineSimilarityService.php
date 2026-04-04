<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CosineSimilarityService
{
    
    private const WEIGHTS = [
        'price' => 0.25,
        'bedrooms' => 0.10,
        'bathrooms' => 0.05,
        'area' => 0.10,
        'location' => 0.25,
        'type' => 0.10,
        'category' => 0.10,
        'purpose' => 0.05,
    ];

    private const VOCAB = [
        'types'      => ['flat', 'house', 'land', 'commercial', 'office', 'warehouse'],
        'categories' => ['residential', 'commercial', 'industrial'],
        'purpose'    => ['buy', 'rent'],
    ];

   
    public function vectorize(Property $p): array
    {
        $lat = $p->latitude ?? 27.7;
        $lng = $p->longitude ?? 85.3;

        $vector = [];

       
        $vector[] = log(1 + ($p->price ?? 0)) / 15;

       
        $vector[] = min(($p->bedrooms ?? 0) / 8, 1);
        $vector[] = min(($p->bathrooms ?? 0) / 6, 1);
        $vector[] = min(($p->area ?? 0) / 50000, 1);

       
        $vector[] = sin(deg2rad($lat));
        $vector[] = cos(deg2rad($lat));
        $vector[] = sin(deg2rad($lng));
        $vector[] = cos(deg2rad($lng));

        
        foreach (self::VOCAB as $field => $terms) {
            $attr = rtrim($field, 's');
            foreach ($terms as $term) {
                $vector[] = $p->{$attr} === $term ? 1.0 : 0.0;
            }
        }

        
        $vector[] = $p->parking ? 1.0 : 0.0;
        $vector[] = $p->garden  ? 1.0 : 0.0;

        return $this->normalize($vector);
    }

    
    public function cosine(array $a, array $b): float
    {
        $dot = 0.0;
        $na = 0.0;
        $nb = 0.0;

        foreach ($a as $i => $va) {
            $vb = $b[$i] ?? 0.0;

            $dot += $va * $vb;
            $na += $va * $va;
            $nb += $vb * $vb;
        }

        $denominator = sqrt($na) * sqrt($nb);

        return $denominator > 1e-10 ? $dot / $denominator : 0.0;
    }

    
    private function normalize(array $v): array
    {
        $norm = sqrt(array_sum(array_map(fn($x) => $x * $x, $v)));

        if ($norm < 1e-10) {
            return $v;
        }

        return array_map(fn($x) => $x / $norm, $v);
    }

   
    public function rankSimilar(Property $target, int $limit = 6): Collection
    {
        $targetVec = $this->vectorize($target);

        return Cache::remember(
            "cosine_sim_{$target->id}_{$target->updated_at}",
            1800,
            function () use ($target, $targetVec, $limit) {

                return Property::approved()
                    ->where('id', '!=', $target->id)
                    ->get()
                    ->map(function ($p) use ($targetVec) {
                        $score = $this->cosine($targetVec, $this->vectorize($p));

                        return [
                            'property' => $p,
                            'score' => $score
                        ];
                    })
                    ->sortByDesc('score')
                    ->take($limit)
                    ->pluck('property');
            }
        );
    }

    
    public function prefsToVector(array $prefs): array
    {
        $mock = new Property();

        foreach ($prefs as $key => $value) {
            $mock->{$key} = $value;
        }

        return $this->vectorize($mock);
    }
}