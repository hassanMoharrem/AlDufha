<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impact extends Model
{
    protected $fillable = ['name', 'image', 'visible'];
    public function getImageAttribute($value)
    {
        return $value ? url('storage/' . $value) : asset('assets/images/images.png');
    }
}
