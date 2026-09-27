<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobCategory extends Model
{
    use HasFactory;

    protected $table = 'job_categories';

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'badge_color',
        'subtext',
        'description',
        'is_active',
        'sort_order',
        'status',
        'requested_by',
        'request_reason',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Scope for active & approved categories that should appear publicly and in job forms.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    /**
     * Scope for pending proposals needing Super Admin review.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending_approval');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get count of active jobs in this category.
     */
    public function getActiveJobsCountAttribute(): int
    {
        return Job::where('division', $this->name)->where('status', 'active')->count();
    }

    /**
     * Get reliable Tailwind CSS style classes for icon, badge and accent.
     */
    public function getColorStylesAttribute(): array
    {
        return match ($this->badge_color) {
            'emerald' => [
                'bg_light' => 'bg-emerald-50 dark:bg-emerald-950/50',
                'border' => 'border-emerald-100 dark:border-emerald-800',
                'text' => 'text-emerald-600 dark:text-emerald-400',
                'badge' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                'hover_bg' => 'group-hover:bg-emerald-600 group-hover:text-white',
                'border_hover' => 'group-hover:border-emerald-500',
            ],
            'indigo' => [
                'bg_light' => 'bg-indigo-50 dark:bg-indigo-950/50',
                'border' => 'border-indigo-100 dark:border-indigo-800',
                'text' => 'text-indigo-600 dark:text-indigo-400',
                'badge' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                'hover_bg' => 'group-hover:bg-indigo-600 group-hover:text-white',
                'border_hover' => 'group-hover:border-indigo-500',
            ],
            'purple' => [
                'bg_light' => 'bg-purple-50 dark:bg-purple-950/50',
                'border' => 'border-purple-100 dark:border-purple-800',
                'text' => 'text-purple-600 dark:text-purple-400',
                'badge' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                'hover_bg' => 'group-hover:bg-purple-600 group-hover:text-white',
                'border_hover' => 'group-hover:border-purple-500',
            ],
            'amber' => [
                'bg_light' => 'bg-amber-50 dark:bg-amber-950/50',
                'border' => 'border-amber-100 dark:border-amber-800',
                'text' => 'text-amber-600 dark:text-amber-400',
                'badge' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                'hover_bg' => 'group-hover:bg-amber-600 group-hover:text-white',
                'border_hover' => 'group-hover:border-amber-500',
            ],
            'sky' => [
                'bg_light' => 'bg-sky-50 dark:bg-sky-950/50',
                'border' => 'border-sky-100 dark:border-sky-800',
                'text' => 'text-sky-600 dark:text-sky-400',
                'badge' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border-sky-200 dark:border-sky-800',
                'hover_bg' => 'group-hover:bg-sky-600 group-hover:text-white',
                'border_hover' => 'group-hover:border-sky-500',
            ],
            'teal' => [
                'bg_light' => 'bg-teal-50 dark:bg-teal-950/50',
                'border' => 'border-teal-100 dark:border-teal-800',
                'text' => 'text-teal-600 dark:text-teal-400',
                'badge' => 'bg-teal-50 text-teal-700 dark:bg-teal-950/60 dark:text-teal-300 border-teal-200 dark:border-teal-800',
                'hover_bg' => 'group-hover:bg-teal-600 group-hover:text-white',
                'border_hover' => 'group-hover:border-teal-500',
            ],
            'rose' => [
                'bg_light' => 'bg-rose-50 dark:bg-rose-950/50',
                'border' => 'border-rose-100 dark:border-rose-800',
                'text' => 'text-rose-600 dark:text-rose-400',
                'badge' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                'hover_bg' => 'group-hover:bg-rose-600 group-hover:text-white',
                'border_hover' => 'group-hover:border-rose-500',
            ],
            default => [
                'bg_light' => 'bg-blue-50 dark:bg-blue-950/50',
                'border' => 'border-blue-100 dark:border-blue-800',
                'text' => 'text-blue-600 dark:text-blue-400',
                'badge' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                'hover_bg' => 'group-hover:bg-blue-600 group-hover:text-white',
                'border_hover' => 'group-hover:border-blue-500',
            ],
        };
    }
}
