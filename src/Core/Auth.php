<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    private Database $db;
    private ?array $user = null;

    public function __construct(Database $db)
    {
        $this->db = $db;
        if (isset($_SESSION['user_id'])) {
            $userModel = new User($this->db);
            $this->user = $userModel->findById((int)$_SESSION['user_id']);
        }
    }

    public function check(): bool
    {
        return $this->user !== null;
    }

    public function user(): ?array
    {
        return $this->user;
    }

    public function id(): ?int
    {
        return $this->user['id'] ?? null;
    }

    public function attempt(string $email, string $password): bool
    {
        $userModel = new User($this->db);
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            if (password_needs_rehash($user['password_hash'], PASSWORD_BCRYPT, ['cost' => 12])) {
                $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $userModel->updatePassword($user['id'], $newHash);
            }
            $this->loginUser($user);
            return true;
        }
        return false;
    }

    public function loginUser(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $this->user = $user;
    }

    public function logout(): void
    {
        if (isset($_COOKIE['remember'])) {
            $this->clearRememberToken($this->user['id']);
            setcookie('remember', '', time() - 3600, '/', '', true, true);
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        $this->user = null;
    }

    public function setRememberToken(int $userId): string
    {
        $selector = bin2hex(random_bytes(8));
        $validator = bin2hex(random_bytes(32));
        $token = $selector . ':' . $validator;
        $hashedValidator = hash('sha256', $validator);
        $this->db->execute(
            'UPDATE users SET remember_token = :token WHERE id = :id',
            ['token' => $selector . ':' . $hashedValidator, 'id' => $userId]
        );
        setcookie('remember', $token, time() + 60*60*24*30, '/', '', true, true);
        return $token;
    }

    public function loginFromRememberCookie(): bool
    {
        if (empty($_COOKIE['remember'])) return false;
        [$selector, $validator] = explode(':', $_COOKIE['remember']);
        $user = $this->db->query(
            'SELECT * FROM users WHERE remember_token LIKE :selector',
            ['selector' => $selector . ':%']
        )->fetch();

        if ($user) {
            [$dbSelector, $dbHash] = explode(':', $user['remember_token']);
            if (hash_equals($dbHash, hash('sha256', $validator))) {
                $this->setRememberToken($user['id']);
                $this->loginUser($user);
                return true;
            }
        }
        setcookie('remember', '', time() - 3600, '/', '', true, true);
        return false;
    }

    private function clearRememberToken(int $userId): void
    {
        $this->db->execute('UPDATE users SET remember_token = NULL WHERE id = :id', ['id' => $userId]);
    }
}