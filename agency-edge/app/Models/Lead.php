<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['name', 'company', 'contact', 'goal', 'message', 'source_page', 'ip_address', 'read_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }
}
