<?php

namespace App\Providers;

use Illuminate\Support\Str;
use App\Models\AdmUser;
use App\Models\Discente;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;

/**
 * MultiLdapUserProvider
 *
 * Gerencia dois fluxos de autenticação:
 * 1. ADM: Apenas números (Servidores/Professores via LDAP).
 * 2. LOCAL: Contém letras/caracteres (Discentes via tabela discentes).
 */
class MultiLdapUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return User::find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        $user = User::where('id', $identifier)->first();

        return $user && $user->getRememberToken() && hash_equals($user->getRememberToken(), $token)
            ? $user
            : null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        if ($user instanceof User) {
            $user->setRememberToken($token);
            $user->save();
        }
    }

    /**
     * Determina a conexão baseada no formato da matrícula.
     */
    protected function detectConnection(string $username): string
    {
        // Se a matrícula for apenas dígitos, assume 'adm' (Servidor)
        // Se houver letras (ex: 20202EX1-GR0071), assume 'local' (Discente)
        return ctype_digit($username) ? 'adm' : 'local';
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials['username']) || empty($credentials['password'])) {
            return null;
        }

        // Sobrescreve ou define a conexão baseada na regra de caracteres
        $connection = $this->detectConnection($credentials['username']);

        Log::debug('Conexão detectada', [
            'username' => $credentials['username'],
            'connection' => $connection
        ]);

        if ($connection === 'local') {
            return $this->getOrCreateLocalUserFromDiscente($credentials['username']);
        }

        $ldapUser = $this->findLdapUser($credentials['username']);

        if (!$ldapUser) {
            Log::debug('Usuário não encontrado no LDAP (ADM)', ['username' => $credentials['username']]);
            return null;
        }

        return $this->getOrCreateLocalUser($ldapUser, $credentials);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Detecta novamente para garantir que a validação siga o fluxo correto
        $connection = $this->detectConnection($credentials['username']);

        if ($connection === 'local') {
            return $this->authenticateWithLocalPassword(
                $credentials['username'],
                $credentials['password']
            );
        }

        if (!$user instanceof LdapAuthenticatable) {
            return false;
        }

        return $this->authenticateInLdap(
            $credentials['username'],
            $credentials['password']
        );
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Não aplicável
    }

    // =========================================================================
    // Métodos de Suporte
    // =========================================================================

    protected function findLdapUser(string $username): mixed
    {
        return AdmUser::query()
            ->in('cn=Users,dc=adm,dc=garanhuns,dc=ifpe')
            ->where('samaccountname', '=', $username)
            ->first();
    }

    protected function authenticateWithLocalPassword(string $matricula, string $password): bool
    {
        $discente = Discente::where('matricula', $matricula)->first();

        if (!$discente) {
            Log::warning('Login Local: Matrícula não encontrada', ['matricula' => $matricula]);
            return false;
        }

        $isValid = Hash::check($password, $discente->senha_responsavel);

        if (!$isValid) {
            Log::warning('Login Local: Senha incorreta', ['matricula' => $matricula]);
        }

        return $isValid;
    }

    protected function authenticateInLdap(string $username, string $password): bool
    {
        $ldapUser = $this->findLdapUser($username);

        if (!$ldapUser) return false;

        return $ldapUser->getConnection()->auth()->attempt(
            $ldapUser->getDn(),
            $password
        );
    }

    protected function getOrCreateLocalUserFromDiscente(string $matricula): ?User
    {
        $discente = Discente::where('matricula', $matricula)->first();

        if (!$discente) return null;

        $user = User::where('username', $matricula)->first();

        if ($user) {
            if ($user->name !== $discente->nome) {
                $user->name = $discente->nome;
                $user->save();
            }
            return $user;
        }

        return User::create([
            'name'     => $discente->nome,
            'username' => $matricula,
            'password' => bcrypt(Str::random(16)),
        ]);
    }

    protected function getOrCreateLocalUser($ldapUser, array $credentials): User
    {
        $email    = $ldapUser->getFirstAttribute('mail') ?? $credentials['username'] . '@garanhuns.ifpe';
        $username = $credentials['username'];

        $user = User::where('email', $email)
            ->orWhere('username', $username)
            ->first();

        if ($user) {
            $this->syncUserAttributes($user, $ldapUser, $email, $username);
            return $user;
        }

        return User::create([
            'name'     => $ldapUser->getFirstAttribute('description') ?? $username,
            'email'    => $email,
            'username' => $username,
            'password' => bcrypt(Str::random(16)),
        ]);
    }

    protected function syncUserAttributes(User $user, $ldapUser, string $email, string $username): void
    {
        $name    = $ldapUser->getFirstAttribute('description') ?? $username;
        $updated = false;

        if ($user->name !== $name)         { $user->name = $name;         $updated = true; }
        if ($user->email !== $email)       { $user->email = $email;       $updated = true; }
        if ($user->username !== $username) { $user->username = $username; $updated = true; }

        if ($updated) {
            $user->save();
        }
    }
}