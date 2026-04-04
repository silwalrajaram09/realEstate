<?php

namespace App\Services;


class CosineSimilarityService
{

    private const FIELD_WEIGHTS = [
        'location' => 3.0,
        'title' => 2.0,
        'description' => 1.0,
    ];


    private const STOPWORDS = [
        'a',
        'an',
        'the',
        'and',
        'or',
        'for',
        'in',
        'on',
        'at',
        'to',
        'of',
        'is',
        'with',
        'near',
        'by',
        'from',
        'this',
        'that',
        'are',
    ];


    public function rerank(\Illuminate\Support\Collection $properties, string $query): \Illuminate\Support\Collection
    {
        if ($properties->isEmpty() || trim($query) === '') {
            return $properties;
        }

        $queryTokens = $this->tokenize($query);
        $queryVector = $this->buildQueryVector($queryTokens);

        // Build corpus with sequential indexing
        $corpus = $properties->values()->map(fn($p) => $this->buildDocument($p))->all();
        $idf = $this->computeIdf($corpus);

        $queryTfIdf = $this->applyIdf($queryVector, $idf);

        // Use values() and iterate with index
        $indexedProperties = $properties->values();

        return $indexedProperties
            ->map(function ($property, $index) use ($corpus, $idf, $queryTfIdf) {
                $docVector = $corpus[$index] ?? [];
                $docTfIdf = $this->applyIdf($docVector, $idf);
                $property->cosine_score = $this->cosine($queryTfIdf, $docTfIdf);
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

        $queryTokens = $this->tokenize($query);
        $queryVector = $this->buildQueryVector($queryTokens);
        $docVector = $this->buildDocument($property);


        return $this->cosine($queryVector, $docVector);
    }

    private function buildDocument(object $property): array
    {
        $vector = [];

        foreach (self::FIELD_WEIGHTS as $field => $weight) {
            $text = (string) ($property->{$field} ?? '');
            $tokens = $this->tokenize($text);
            $tf = $this->termFrequency($tokens);

            foreach ($tf as $term => $freq) {
                $vector[$term] = ($vector[$term] ?? 0.0) + ($freq * $weight);
            }
        }

        return $vector;
    }


    private function buildQueryVector(array $tokens): array
    {
        return $this->termFrequency($tokens);
    }

    private function computeIdf(array $corpus): array
    {
        $N = count($corpus);
        $df = []; // document frequency per term

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

    private function cosine(array $a, array $b): float
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

        $magA = sqrt(array_sum(array_map(fn($v) => $v ** 2, $a)));
        $magB = sqrt(array_sum(array_map(fn($v) => $v ** 2, $b)));

        if ($magA === 0.0 || $magB === 0.0) {
            return 0.0;
        }

        return round($dot / ($magA * $magB), 6);
    }


    private function tokenize(string $text): array
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s]/u', ' ', $text);
        $tokens = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Basic stemming (plural removal)
        $tokens = array_map(function ($token) {
            return preg_replace('/(s|es|ing|ed)$/', '', $token);
        }, $tokens);

        return array_values(
            array_filter($tokens, fn($t) => !in_array($t, self::STOPWORDS, true) && strlen($t) > 1)
        );
    }


    private function termFrequency(array $tokens): array
    {
        if (empty($tokens)) {
            return [];
        }

        $counts = array_count_values($tokens);
        $total = count($tokens);

        return array_map(fn($c) => $c / $total, $counts);
    }
}