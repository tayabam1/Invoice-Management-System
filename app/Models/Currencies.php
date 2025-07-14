<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    protected $fillable = [
        'company_id',
        'iso',
        'iso3',
        'currency_name',
        'currency_code',
        'currency_symbol',
        'native_name',
        'exchange_rate',
        'is_cryptocurrency',
        'usd_price',
        'no_of_decimal',
        'thousand_separator',
        'decimal_separator',
        'decimals',
        'currency_position',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:8',
        'usd_price' => 'float',
        'no_of_decimal' => 'integer',
        'decimals' => 'integer',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'currency_id');
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->currency_code} - {$this->currency_name}";
    }

    public function formatAmount($amount)
    {
        $formatted = number_format($amount, $this->no_of_decimal, $this->decimal_separator ?: '.', $this->thousand_separator ?: ',');

        switch ($this->currency_position) {
            case 'left':
                return $this->currency_symbol . $formatted;
            case 'right':
                return $formatted . $this->currency_symbol;
            case 'left_with_space':
                return $this->currency_symbol . ' ' . $formatted;
            case 'right_with_space':
                return $formatted . ' ' . $this->currency_symbol;
            default:
                return $this->currency_symbol . $formatted;
        }
    }
}
