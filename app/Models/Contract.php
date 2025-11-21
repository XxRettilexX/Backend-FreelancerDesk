<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimate_id',
        'pdf_url',
        'signed_by_client',
        'signed_at'
    ];

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }
}
