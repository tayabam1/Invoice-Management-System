<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_id', 'currency_id', 'status', 'due_date', 'total', 'logo',
    ];

    protected $appends = ['logo_url', 'formatted_total'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currencies::class, 'currency_id');
    }

    public function lineItems()
    {
        return $this->hasMany(InvoiceLineItem::class);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getFormattedTotalAttribute()
    {
        if ($this->currency) {
            return $this->currency->formatAmount($this->total);
        }
        return '$' . number_format($this->total, 2);
    }
}
