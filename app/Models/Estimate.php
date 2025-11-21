<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_id',
        'project_id',
        'title',
        'description',
        'subtotal',
        'vat',
        'total',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(EstimateItem::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}
