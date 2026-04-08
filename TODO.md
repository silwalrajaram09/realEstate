# Real Estate Project Performance Optimization TODO

## 🔥 IMMEDIATE (Today - 2 hours total)

### 1. Clean Duplicate Services [15min]

```
rm app/services/CosineSimilarityService\ 2.php
rm app/services/Cosinesimilarityservice.php  # Keep PropertyRecommendationService
```

**Verify**: No import errors after delete

### 2. Run Diagnostics [5min]

```bash
php artisan recommendations:report --sample-users=50
php artisan recommendations:refresh --incremental --since-hours=2
```

### 3. Add Critical Indexes [10min]

```sql
-- In phpMyAdmin/pgAdmin or migration
CREATE INDEX idx_properties_perf ON properties(status, listing_status, is_featured, views_count, created_at)
WHERE status='approved' AND listing_status='available';

CREATE INDEX idx_property_vectors_fresh ON property_vectors(normalized_at)
WHERE normalized_at > NOW() - INTERVAL '7 days';
```

### 4. Test Buyer Flow [10min]

```
Browse: /buyer/suggestions → /buyer/properties → property detail
Check: Infinite scroll works? Rec scores >0.2? No errors?
```

## 🟡 WEEK 1 - Productionize

### 5. Cache Hot Vectors [30min]

```php
// In PropertyRecommendationService.php
private array $vectorCache = [];
public function getVectorByPropertyId(int $id): ?array {
    if (!isset($this->vectorCache[$id])) {
        $this->vectorCache[$id] = Cache::remember("propvec:{$id}", 3600, fn() =>
            PropertyVector::find($id)?->vector_array);
    }
    return $this->vectorCache[$id];
}
```

### 6. Redis for User Profiles [45min]

```
composer require predis/predis
php artisan cache:config  # redis driver
```

Cache personalized vectors 24h.

### 7. Monitor Commands [15min]

```bash
# storage/logs/scheduler.log
php artisan schedule:work  # Test jobs
```

## 🔮 SCALE PREP (Week 2+)

### 8. PGVector Extension [2h]

```
-- For 100k+ properties ANN search
CREATE EXTENSION vector;
ALTER TABLE property_vectors ADD COLUMN embedding vector(9);  -- feature dim
```

### 9. Queue Vector Refresh [1h]

```
php artisan make:job RefreshPropertyVectors
// Move logic from command to queued job
```

## ✅ DONE CHECKLIST

- [ ] Duplicates deleted
- [ ] Diagnostics: avg_similarity > 0.25
- [ ] Indexes added
- [ ] Buyer flow tested

**Run `php artisan recommendations:report` weekly. Target: 0.35+ similarity**
