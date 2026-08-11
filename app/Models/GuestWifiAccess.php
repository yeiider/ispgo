<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestWifiAccess extends Model
{
    use HasFactory;

    protected $table = 'guest_wifi_access';

    protected $fillable = [
        'full_name',
        'phone_number',
        'email',
        'router_id',
        'otp_code',
        'otp_method',
        'otp_expires_at',
        'is_verified',
        'verified_at',
        'access_expires_at',
        'ip_address',
        'mac_address',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    /**
     * Verifica si el OTP es válido y no ha expirado
     */
    public function isOtpValid(string $code): bool
    {
        return $this->otp_code === $code
            && !$this->is_verified
            && $this->otp_expires_at->isFuture();
    }

    /**
     * Marca el acceso como verificado y establece la expiración de acceso (24h)
     */
    public function markAsVerified(): void
    {
        $this->is_verified = true;
        $this->verified_at = now();
        $this->access_expires_at = now()->addDay();
        $this->save();
    }

    /**
     * Verifica si el usuario tiene acceso válido activo (verificado y no expirado)
     */
    public function hasActiveAccess(): bool
    {
        return $this->is_verified
            && $this->access_expires_at
            && $this->access_expires_at->isFuture();
    }

    /**
     * Busca un registro de acceso válido para el día actual por email
     */
    public static function findActiveByEmail(string $email, int $routerId): ?self
    {
        return self::where('email', $email)
            ->where('router_id', $routerId)
            ->where('is_verified', true)
            ->where('access_expires_at', '>', now())
            ->first();
    }

    /**
     * Busca un registro de acceso válido para el día actual por teléfono
     */
    public static function findActiveByPhone(string $phone, int $routerId): ?self
    {
        return self::where('phone_number', $phone)
            ->where('router_id', $routerId)
            ->where('is_verified', true)
            ->where('access_expires_at', '>', now())
            ->first();
    }

    /**
     * Genera un código OTP de 6 dígitos
     */
    public static function generateOtpCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
