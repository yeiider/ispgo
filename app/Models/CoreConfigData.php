<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreConfigData extends Model
{
    use HasFactory;

    protected $table = 'core_config_data';

    protected $fillable = [
        'scope_id',
        'path',
        'value',
    ];

    /**
     * The default scope value.
     *
     * @var int
     */
    protected $attributes = [
        'scope_id' => 0,
    ];

    /**
     * Get the value of a configuration by path with a default scope_id of 0.
     *
     * @param string $path
     * @param int $scopeId
     * @return string|null
     */
    public static function getValueByPath(string $path, int $scopeId = 0): ?string
    {
        return self::where('path', $path)
            ->where('scope_id', $scopeId)
            ->value('value');
    }
}
