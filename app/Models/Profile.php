<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'tagline',
        'summary',
        'email',
        'phone',
        'address',
        'photo',
        'logo',
        'logo_dark',
        'facebook',
        'linkedin',
        'github',
        'twitter',
        'resume_url',
    ];

    /**
     * Resolve the logo to display in LIGHT mode.
     * Falls back to the dark logo if no light logo is set.
     */
    public function logoUrlForLight(): ?string
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return asset('storage/' . $this->logo);
        }

        if ($this->logo_dark && Storage::disk('public')->exists($this->logo_dark)) {
            return asset('storage/' . $this->logo_dark);
        }

        return null;
    }

    /**
     * Resolve the logo to display in DARK mode.
     * Falls back to the light logo if no dark logo is set.
     */
    public function logoUrlForDark(): ?string
    {
        if ($this->logo_dark && Storage::disk('public')->exists($this->logo_dark)) {
            return asset('storage/' . $this->logo_dark);
        }

        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return asset('storage/' . $this->logo);
        }

        return null;
    }

    /**
     * Whether the light and dark logos resolve to the same asset
     * (i.e. only one logo is uploaded, used for both themes).
     */
    public function hasSingleLogo(): bool
    {
        return $this->logoUrlForLight() === $this->logoUrlForDark();
    }
}
