<?php

namespace App\Models;

use App\Traits\HasSignedUrls;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAttachment extends Model
{
    use HasFactory, HasSignedUrls;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'user_id',
    ];

    protected $appends = ['image_url'];

    protected function imageUrl(): Attribute
    {
        return $this->signedUrlAttribute('file_path', 's3', 60);
    }
    /**
     * Get the ticket that owns the attachment.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who uploaded the attachment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who uploaded the attachment (alias for backward compatibility).
     */
    public function uploader(): BelongsTo
    {
        return $this->user();
    }

    /**
     * Get the full URL to the file.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Determine if the file is an image.
     *
     * @return bool
     */
    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Get the human-readable file size.
     *
     * @return string
     */
    public function getHumanFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
