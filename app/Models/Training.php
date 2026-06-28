<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Training extends Model
{
    use HasFactory;

    protected $table = 'trainings';

    protected $fillable = [
        'title',
        'organization',
        'description',
        'long_description',
        'image',
        'certificate_url',
        'duration',
        'start_year',
        'end_year',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Auto-generate a slug from the title when creating a training.
     */
    protected static function booted(): void
    {
        static::creating(function (Training $training) {
            if (empty($training->slug)) {
                $training->slug = $training->generateUniqueSlug($training->title);
            }
        });

        static::updating(function (Training $training) {
            if ($training->isDirty('title') && empty($training->slug)) {
                $training->slug = $training->generateUniqueSlug($training->title);
            }
        });
    }

    /**
     * Generate a URL-safe slug that is unique in the table.
     */
    public function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (self::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    /**
     * Get the public URL for this training detail page.
     */
    public function publicUrl(): string
    {
        return route('training.show', $this->slug);
    }

    /**
     * Formatted year range, e.g. "2023 — 2024" or "2023 — Present".
     */
    public function yearRange(): string
    {
        $start = $this->start_year ?: '';
        $end = $this->end_year ?: '';

        if ($start && $end) {
            return $start . ' — ' . $end;
        }

        if ($start) {
            return $start . ' — Present';
        }

        return $this->duration ?: '';
    }
}
