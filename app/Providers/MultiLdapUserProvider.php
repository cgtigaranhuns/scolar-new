<?php

namespace App\Providers;

use Illuminate\Support\Str;
use App\Models\AdmUser;
use App\Models\LabsUser;
use App\Models\Discente;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Log;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;

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

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials['username']) || empty($credentials['password'])) {
            return null;
        }

        $connection = $credentials['connection'] ?? 'adm';

        Log::debug('Buscando usuário LDAP', [
            'username'   => $credentials['username'],
            'connection' => $connection,
        ]);

        $ldapUser = $this->findLdapUser($credentials['username'], $connection);

        if (! $ldapUser) {
            Log::debug('Usuário não encontrado no LDAP', ['connection' => $connection]);
            return null;
        }

        return $this->getOrCreateLocalUser($ldapUser, $credentials);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (! $user instanceof LdapAuthenticatable) {
            return false;
        }

        $connection = $credentials['connection'] ?? 'adm';

        // Conexão labs: valida senha pelo campo senha_responsavel na tabela discentes
        if ($connection === 'labs') {
            return $this->authenticateWithLocalPassword(
                $credentials['username'],
                $credentials['password']
            );
        }

        // Conexão adm: valida senha no LDAP normalmente
        return $this->authenticateInLdap(
            $credentials['username'],
            $credentials['password'],
            $connection
        );
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Senhas gerenciadas pelo LDAP externamente, sem rehash local necessário
    }

    // -------------------------------------------------------------------------
    // Métodos internos
    // -------------------------------------------------------------------------

    protected function findLdapUser(string $username, string $connection): mixed
    {
        Log::info('Iniciando busca LDAP', ['username' => $username, 'connection' => $connection]);

        if ($connection === 'adm') {
            $user = AdmUser::query()
                ->in('cn=Users,dc=adm,dc=garanhuns,dc=ifpe')
                ->where('samaccountname', '=', $username)
                ->first();
        } else {
            $user = LabsUser::query()
                ->in('ou=Discentes,dc=labs,dc=garanhuns,dc=ifpe')
                ->where('samaccountname', '=', $username)
                ->first();
        }

        if ($user) {
            Log::info('Usuário LDAP encontrado', ['dn' => $user->getDn()]);
        }

        return $user ?? null;
    }

    protected function authenticateWithLocalPassword(string $matricula, string $password): bool
    {
        Log::info('Validando senha local para discente', ['matricula' => $matricula]);

        $discente = Discente::where('matricula', $matricula)->first();

        if (! $discente) {
            Log::warning('Discente não encontrado na tabela local', ['matricula' => $matricula]);
            return false;
        }

        $result = \Illuminate\Support\Facades\Hash::check($password, $discente->senha_responsavel);

        Log::info('Resultado da validação local', ['sucesso' => $result]);

        return $result;
    }

    protected function authenticateInLdap(string $username, string $password, string $connection): bool
    {
        Log::info('Autenticando no LDAP', ['username' => $username, 'connection' => $connection]);

        $ldapUser = $this->findLdapUser($username, $connection);

        if (! $ldapUser) {
            Log::warning('Usuário LDAP não encontrado na validação de credenciais');
            return false;
        }

        $result = $ldapUser->getConnection()->auth()->attempt(
            $ldapUser->getDn(),
            $password
        );

        Log::info('Resultado da autenticação LDAP', ['sucesso' => $result]);

        return $result;
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

        Log::info('Criando novo usuário local', ['username' => $username]);

        $user = User::create([
            'name'     => $ldapUser->getFirstAttribute('description') ?? $username,
            'email'    => $email,
            'username' => $username,
            'password' => bcrypt(Str::random(16)),
        ]);

        Log::info('Novo usuário criado', ['id' => $user->id]);

        return $user;
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
            Log::info('Dados do usuário sincronizados', ['id' => $user->id]);
        }
    }
}