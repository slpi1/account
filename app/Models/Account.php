<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id', 'site_id', 'account',
    ];

    public function site()
    {
        return $this->hasOne(Site::class, 'id', 'site_id');
    }
}
