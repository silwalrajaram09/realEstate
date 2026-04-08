<?php

$file = '/Users/rajaramsilwal/Documents/project/realEstate/app/services/PropertyRecommendationService.php';
$content = file_get_contents($file);

// Replace FEATURE_KEYS
$content = preg_replace(
    "/private const FEATURE_KEYS = \[.*?\];/s",
    "private const FEATURE_KEYS = [
        'price', 'area', 'bedrooms', 'bathrooms',
        'type_house', 'type_flat', 'type_land', 'type_commercial', 'type_office', 'type_warehouse',
        'category_residential', 'category_commercial', 'category_industrial',
        'purpose_buy', 'purpose_rent',
        'latitude', 'longitude',
    ];

    private const FEATURE_WEIGHTS = [
        'price' => 3.0,
        'area' => 1.5,
        'bedrooms' => 1.0,
        'bathrooms' => 0.5,
        'type_house' => 2.0, 'type_flat' => 2.0, 'type_land' => 2.0, 'type_commercial' => 2.0, 'type_office' => 2.0, 'type_warehouse' => 2.0,
        'category_residential' => 1.5, 'category_commercial' => 1.5, 'category_industrial' => 1.5,
        'purpose_buy' => 4.0, 'purpose_rent' => 4.0,
        'latitude' => 1.0, 'longitude' => 1.0,
    ];",
    $content
);

// Replace buildRawVector
$content = preg_replace(
    "/private function buildRawVector.*?return \[.*?\];\n    }/s",
    "private function buildRawVector(Property \$property): array
    {
        \$price = max(0.0, (float) (\$property->price ?? 0));
        \$area = max(0.0, (float) (\$property->area ?? 0));
        \$lat = (float) (\$property->latitude ?? 0);
        \$lng = (float) (\$property->longitude ?? 0);

        return [
            'price' => log(1 + \$price),
            'area' => sqrt(\$area),
            'bedrooms' => min(10, max(0, (int) (\$property->bedrooms ?? 0))),
            'bathrooms' => min(10, max(0, (int) (\$property->bathrooms ?? 0))),
            'type_house' => (\$property->type === 'house') ? 1.0 : 0.0,
            'type_flat' => (\$property->type === 'flat') ? 1.0 : 0.0,
            'type_land' => (\$property->type === 'land') ? 1.0 : 0.0,
            'type_commercial' => (\$property->type === 'commercial') ? 1.0 : 0.0,
            'type_office' => (\$property->type === 'office') ? 1.0 : 0.0,
            'type_warehouse' => (\$property->type === 'warehouse') ? 1.0 : 0.0,
            'category_residential' => (\$property->category === 'residential') ? 1.0 : 0.0,
            'category_commercial' => (\$property->category === 'commercial') ? 1.0 : 0.0,
            'category_industrial' => (\$property->category === 'industrial') ? 1.0 : 0.0,
            'purpose_buy' => (\$property->purpose === 'buy') ? 1.0 : 0.0,
            'purpose_rent' => (\$property->purpose === 'rent') ? 1.0 : 0.0,
            'latitude' => \$lat,
            'longitude' => \$lng,
        ];
    }",
    $content
);

// Replace normalizeVector
$content = preg_replace(
    "/private function normalizeVector.*?return \\\$normalized;\n    }/s",
    "private function normalizeVector(array \$raw, array \$bounds): array
    {
        \$normalized = [];
        foreach (self::FEATURE_KEYS as \$key) {
            \$min = \$bounds[\$key]['min'] ?? 0.0;
            \$max = \$bounds[\$key]['max'] ?? 0.0;
            \$value = \$raw[\$key] ?? 0.0;
            
            \$scaled = (\$max - \$min) == 0.0 ? 0.0 : (\$value - \$min) / (\$max - \$min);
            \$weight = self::FEATURE_WEIGHTS[\$key] ?? 1.0;
            
            // Weighting makes the vector 'longer' in the important dimensions
            \$normalized[\$key] = \$scaled * \$weight;
        }
        return \$normalized;
    }",
    $content
);

// Replace / Remove encodeType, encodeCategory, encodePurpose
$content = preg_replace("/private function encodeType.*?}\n\n/s", "", $content);
$content = preg_replace("/private function encodeCategory.*?}\n\n/s", "", $content);
$content = preg_replace("/private function encodePurpose.*?}/s", "", $content);

file_put_contents($file, $content);

?>
