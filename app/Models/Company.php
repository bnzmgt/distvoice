<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'legal_name',
        'email',
        'phone',
        'address',
        'logo',
        'is_default',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->first();
    }

    protected static function booted()
    {
        static::saving(function (Company $company) {
            if ($company->is_default) {
                static::where('id', '!=', $company->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
