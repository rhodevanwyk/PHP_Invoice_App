<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Models\User;

class AuthController extends Controller
{
    private Database $db;
    private Auth $auth;

    public function __construct()
    {
        global $db, $auth;
        $this->db = $db;
        $this->auth = $auth;
    }

    public function showRegister(): void
    {
        $csrfToken = $this->generateCsrfToken();
        $this->view('auth/register', ['csrfToken' => $csrfToken, 'errors' => []]);
    }

    public function register(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrfToken($token)) {
            http_response_code(419);
            die('CSRF token mismatch.');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        $errors = [];
        if ($name === '') $errors['name'] = 'Name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email required.';
        if (strlen($password) < 8) $errors['password'] = 'Password must be at least 8 characters.';
        if ($password !== $passwordConfirmation) $errors['password'] = 'Passwords do not match.';

        $userModel = new User($this->db);
        if (empty($errors) && $userModel->findByEmail($email)) {
            $errors['email'] = 'Email already registered.';
        }

        if (!empty($errors)) {
            $csrfToken = $this->generateCsrfToken();
            $this->view('auth/register', ['csrfToken' => $csrfToken, 'errors' => $errors, 'old' => $_POST]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $userId = $userModel->create([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        $user = $userModel->findById($userId);
        $this->auth->loginUser($user);
        $this->redirect('/dashboard');
    }
}