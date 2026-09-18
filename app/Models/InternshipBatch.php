<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class InternshipBatch extends Model
{
    use HasFactory;

    protected $table = 'internship_batches';

    protected $fillable = [
        'company_id',
        'batch_name',
        'start_date',
        'end_date',
        'target_hours',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_hours' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Find existing batch or create a new one automatically.
     */
    public static function findOrCreateByName(string $name, ?int $companyId = null, array $attributes = []): self
    {
        $name = trim($name);
        if (empty($name)) {
            $name = 'Batch 1 - ' . date('Y');
        }

        $query = static::where('batch_name', $name);
        if ($companyId) {
            $query->where(function ($q) use ($companyId) {
                $q->whereNull('company_id')->orWhere('company_id', $companyId);
            });
        }

        $existing = $query->first();
        if ($existing) {
            return $existing;
        }

        return static::create(array_merge([
            'company_id' => $companyId,
            'batch_name' => $name,
            'start_date' => $attributes['start_date'] ?? now()->startOfMonth()->toDateString(),
            'end_date' => $attributes['end_date'] ?? now()->addMonths(6)->endOfMonth()->toDateString(),
            'target_hours' => $attributes['target_hours'] ?? 400,
            'status' => $attributes['status'] ?? 'active',
            'created_by' => $attributes['created_by'] ?? (auth()->check() ? auth()->id() : null),
        ], $attributes));
    }

    /**
     * Get active batch names list for dropdowns across the application.
     */
    public static function getActiveBatches(?int $companyId = null): Collection
    {
        $query = static::where('status', '!=', 'closed');
        if ($companyId) {
            $query->where(function ($q) use ($companyId) {
                $q->whereNull('company_id')->orWhere('company_id', $companyId);
            });
        }

        $batches = $query->orderBy('start_date', 'desc')->pluck('batch_name');

        // Also check if any older batches exist in InternshipPeriod, Job, or InternshipCurriculum
        $fallback = InternshipPeriod::whereNotNull('period_name')->pluck('period_name')
            ->merge(Job::whereNotNull('batch')->where('batch', '!=', '')->pluck('batch'))
            ->merge(InternshipCurriculum::whereNotNull('batch')->where('batch', '!=', '')->pluck('batch'))
            ->filter();

        $merged = $batches->merge($fallback)->unique()->values();

        if ($merged->isEmpty()) {
            return collect([
                'Batch 1 - Semester Genap 2026',
                'Batch 2 - Semester Ganjil 2026',
            ]);
        }

        return $merged;
    }
}
