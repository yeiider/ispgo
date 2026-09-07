<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Crea un Personal Access Token PERMANENTE (expires_at = NULL) para
 * servicios externos (n8n, scripts, integraciones, la web).
 *
 * Los tokens generados por login (password grant) y client_credentials
 * expiran según AppServiceProvider (15/30 días). Este comando genera un
 * token que NO expira y solo se revoca manualmente.
 *
 * Uso:
 *   php artisan service:token n8n-facturacion
 *   php artisan service:token web-consultas --user=admin@ispgo.com
 *
 * Revocar:
 *   php artisan service:token --revoke n8n-facturacion
 */
class CreateServiceToken extends Command
{
    protected $signature = 'service:token
        {name : Nombre del token (ej. n8n-facturacion)}
        {--user= : Email del usuario dueño del token (default: primer super-admin)}
        {--revoke : Revocar el token con ese nombre en lugar de crear}';

    protected $description = 'Crea o revoca un Personal Access Token permanente para servicios externos';

    public function handle(): int
    {
        $name = $this->argument('name');

        if ($this->option('revoke')) {
            return $this->revoke($name);
        }

        $email = $this->option('user');
        $user = $email
            ? User::where('email', $email)->first()
            : User::role(['super-admin'])->first();

        if (!$user) {
            $this->error('Usuario no encontrado' . ($email ? " con email: {$email}" : ' (no hay super-admin)'));
            return self::FAILURE;
        }

        // Asegurar que exista el personal access client (necesario para createToken)
        if (!DB::table('oauth_personal_access_clients')->exists()) {
            $this->warn('No existe personal access client — creándolo...');
            $this->callSilently('passport:client', ['--personal' => true, '--name' => 'ISPGO Personal Access Client']);
        }

        $token = $user->createToken($name, ['*']);

        // Hacerlo permanente: expires_at = NULL
        DB::table('oauth_access_tokens')
            ->where('id', $token->token->id)
            ->update(['expires_at' => null]);

        $this->info("Token permanente creado para: {$user->email}");
        $this->info("Nombre: {$name}");
        $this->newLine();
        $this->line('COPIA ESTE TOKEN (solo se muestra una vez):');
        $this->newLine();
        $this->info($token->accessToken);
        $this->newLine();
        $this->warn("Uso: Authorization: Bearer {$token->accessToken}");
        $this->warn("Revocar: php artisan service:token {$name} --revoke");

        return self::SUCCESS;
    }

    protected function revoke(string $name): int
    {
        $updated = DB::table('oauth_access_tokens')
            ->where('name', $name)
            ->whereNull('revoked')
            ->update(['revoked' => true]);

        if ($updated === 0) {
            $this->warn("No se encontró ningún token activo con nombre: {$name}");
            return self::SUCCESS;
        }

        $this->info("Token '{$name}' revocado ({$updated} token(s)).");
        return self::SUCCESS;
    }
}
