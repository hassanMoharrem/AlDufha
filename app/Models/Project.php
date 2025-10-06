<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'sub_description',
        'description',
        'images',
        'visible',
    ];

    protected $casts = [
        'images' => 'array',
        'visible' => 'boolean',
    ];
    public function getImagesAttribute($value)
    {
        $decoded = json_decode($value, true);
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }
        return $decoded ? array_map(function ($image) {
            // إذا كان الرابط يبدأ بـ http اعتبره رابط كامل ولا تضف عليه url('storage/')
            if (is_string($image) && (str_starts_with($image, 'http://') || str_starts_with($image, 'https://'))) {
                return $image;
            }
            return $image ? url('storage/' . ltrim($image, '/')) : asset('assets/img/logo.png');
        }, $decoded) : [];
    }
}
