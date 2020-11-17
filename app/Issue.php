<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'code',
        'issuer_id',
        'body',
        'closed',
        'closed_at',
        'status',
        'title'
    ];

    protected $casts = [
        'closed_at' => 'date'
    ];

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issuer_id');
    }
}
