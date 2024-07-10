<?php

namespace App\Models\PageBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;

    protected $table;

    protected $fillable = [
        'id',
        'name',
        'title',
        'route',
        'layout',
        'data',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('pagebuilder.storage.database.prefix') . 'pages';
    }

    public function translations()
    {
        return $this->hasMany(PageTranslation::class, 'page_id');
    }

    public function getPage()
    {
        return $this;
    }
}
