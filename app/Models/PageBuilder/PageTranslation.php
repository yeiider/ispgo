<?php

namespace App\Models\PageBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    use HasFactory;

    protected $table;

    protected $fillable = [
        'page_id',
        'locale',
        'title',
        'meta_title',
        'meta_description',
        'route',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('pagebuilder.storage.database.prefix') . 'page_translations';
    }

    public function page()
    {
        return $this->belongsTo(Pages::class, 'page_id');
    }
}
