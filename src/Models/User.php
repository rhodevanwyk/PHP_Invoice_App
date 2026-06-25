<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class User
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array
    {
        return $this->db->query('SELECT * FROM users WHERE email = :email', ['email' => $email])->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        return $this->db->query('SELECT * FROM users WHERE id = :id', ['id' => $id])->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $this->db->execute(
            'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password)',
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password_hash'],
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function updatePassword(int $id, string $newHash): void
    {
        $this->db->execute('UPDATE users SET password_hash = :hash WHERE id = :id', ['hash' => $newHash, 'id' => $id]);
    }

    public function createPasswordReset(string $email): string
    {
        $this->db->execute('DELETE FROM password_resets WHERE email = :email', ['email' => $email]);
        $rawToken = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $rawToken);
        $this->db->execute(
            'INSERT INTO password_resets (email, token, created_at) VALUES (:email, :token, NOW())',
            ['email' => $email, 'token' => $hashedToken]
        );
        return $rawToken;
    }

    public function findPasswordResetByRawToken(string $rawToken): ?array
    {
        $hashed = hash('sha256', $rawToken);
        return $this->db->query(
            'SELECT * FROM password_resets WHERE token = :token',
            ['token' => $hashed]
        )->fetch() ?: null;
    }

    public function deletePasswordResetByEmail(string $email): void
    {
        $this->db->execute('DELETE FROM password_resets WHERE email = :email', ['email' => $email]);
    }
}