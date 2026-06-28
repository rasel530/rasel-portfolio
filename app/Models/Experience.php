<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Experience extends Model
{
    use HasFactory;

    protected $table = 'experiences';

    protected $fillable = [
        'position',
        'company',
        'company_address',
        'description',
        'start_date',
        'end_date',
        'is_current',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Calculate the total experience across all records.
     *
     * Iterates every experience row, computes the duration between
     * start_date and end_date (or today if is_current), sums the days,
     * and returns a human-friendly array.
     *
     * @return array{years: int, months: int, days: int, formatted: string, raw_days: int}
     */
    public static function totalExperience(): array
    {
        $totalDays = 0;
        $now = Carbon::now();

        foreach (self::all() as $exp) {
            if (! $exp->start_date) {
                continue;
            }

            $start = $exp->start_date;

            if ($exp->is_current) {
                $end = $now;
            } elseif ($exp->end_date) {
                $end = $exp->end_date;
            } else {
                continue;
            }

            if ($end->greaterThan($start)) {
                $totalDays += (int) $start->diffInDays($end);
            }
        }

        return self::formatDuration($totalDays);
    }

    /**
     * Convert a number of days into years / months / days plus a
     * human-readable string.
     *
     * @return array{years: int, months: int, days: int, formatted: string, raw_days: int}
     */
    private static function formatDuration(int $totalDays): array
    {
        $years = floor($totalDays / 365);
        $remainingAfterYears = $totalDays - ($years * 365);
        $months = floor($remainingAfterYears / 30);

        // Build a readable label.
        $parts = [];

        if ($years > 0) {
            $parts[] = $years . ' ' . ($years == 1 ? 'Year' : 'Years');
        }

        if ($months > 0) {
            $parts[] = $months . ' ' . ($months == 1 ? 'Month' : 'Months');
        }

        if (empty($parts)) {
            $parts[] = '< 1 Month';
        }

        return [
            'years' => (int) $years,
            'months' => (int) $months,
            'days' => $totalDays,
            'formatted' => implode(' ', $parts),
            'raw_days' => $totalDays,
        ];
    }
}
