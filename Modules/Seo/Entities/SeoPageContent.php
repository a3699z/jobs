<?php

namespace Modules\Seo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeoPageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'language_code',
        'title',
        'description',
        'image'
    ];

    protected static function newFactory()
    {
        return \Modules\Seo\Database\factories\SeoPageContentFactory::new();
    }
}
