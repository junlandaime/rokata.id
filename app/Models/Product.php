<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-sm bg-gradient-secondary">Draft</span>';
        }
        return '<span class="badge badge-sm bg-gradient-success">Aktif</span>';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
