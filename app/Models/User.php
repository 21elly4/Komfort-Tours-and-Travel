<?php

namespace Komfort\App\Models;

class User extends BaseModel
{
    protected string $table = 'users';

    public function create(array $data): int
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        if (!isset($data['uuid'])) {
            $data['uuid'] = $this->generateUuid();
        }
        
        return parent::create($data);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', '=', $email)[0] ?? null;
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        return $this->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);
    }

    public function getTravelers(): array
    {
        return $this->where('role', '=', 'traveler');
    }

    public function getAdmins(): array
    {
        return $this->where('role', '=', 'admin');
    }

    public function activate(int $userId): bool
    {
        return $this->update($userId, ['is_active' => true]);
    }

    public function deactivate(int $userId): bool
    {
        return $this->update($userId, ['is_active' => false]);
    }

    public function markEmailVerified(int $userId): bool
    {
        return $this->update($userId, ['email_verified_at' => date('Y-m-d H:i:s')]);
    }

    private function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
