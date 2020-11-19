<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'name', 'host', 'description',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
