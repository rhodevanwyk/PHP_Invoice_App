<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Models\User;
use App\Services\MailService;

class AuthController extends Controller
{
    private Database $db;
    private Auth $auth;
    private MailService $mail;

    public function __construct(array $services)
    {
        $this->db   = $services['db'];
        $this->auth = $services['auth'];
        $this->mail = $services['mail'];
    }

    public function showRegister(): void
    {
        if ($this->auth->check()) {
            $this->redirect('/dashboard');
            return;
        }

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

    public function showLogin(): void
    {
        if ($this->auth->check()) {
            $this->redirect('/dashboard');
            return;
        }

        $flash = $_SESSION['flash_message'] ?? '';
        unset($_SESSION['flash_message']);
        $csrfToken = $this->generateCsrfToken();
        $this->view('auth/login', [
            'csrfToken' => $csrfToken,
            'flash' => $flash,
            'errors' => []
        ]);
    }

    public function login(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrfToken($token)) {
            http_response_code(419);
            die('CSRF token mismatch.');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        $errors = [];
        if ($email === '' || $password === '') {
            $errors['general'] = 'Email and password are required.';
        }

        if (empty($errors) && !$this->auth->attempt($email, $password)) {
            $errors['general'] = 'Invalid credentials.';
        }

        if (!empty($errors)) {
            $csrfToken = $this->generateCsrfToken();
            $this->view('auth/login', ['csrfToken' => $csrfToken, 'flash' => '', 'errors' => $errors]);
            return;
        }

        if ($remember) {
            $this->auth->setRememberToken($this->auth->id());
        }

        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        $this->auth->logout();
        $this->redirect('/');
    }

    public function showForgotPassword(): void
    {
        $csrfToken = $this->generateCsrfToken();
        $this->view('auth/forgot-password', ['csrfToken' => $csrfToken, 'error' => '', 'success' => '']);
    }

    public function sendResetLink(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrfToken($token)) {
            http_response_code(419);
            die('CSRF token mismatch.');
        }

        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $csrfToken = $this->generateCsrfToken();
            $this->view('auth/forgot-password', [
                'csrfToken' => $csrfToken,
                'error' => 'Please enter a valid email address.',
                'success' => ''
            ]);
            return;
        }

        $userModel = new User($this->db);
        $user = $userModel->findByEmail($email);

        $successMsg = 'If that email is registered, a reset link has been sent.';
        if (!$user) {
            $csrfToken = $this->generateCsrfToken();
            $this->view('auth/forgot-password', [
                'csrfToken' => $csrfToken,
                'error' => '',
                'success' => $successMsg
            ]);
            return;
        }

        $rawToken = $userModel->createPasswordReset($email);
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $resetLink = $scheme . '://' . $host . url('/reset-password?token=' . urlencode($rawToken));

        $subject = 'Reset Your InvoiceHub Password';
        $body = "
            <h2>Password Reset Request</h2>
            <p>Click the link below to reset your password. This link is valid for 1 hour.</p>
            <p><a href=\"{$resetLink}\">Reset Password</a></p>
            <p>If you didn't request this, ignore this email.</p>
        ";

        $sent = $this->mail->send($email, $subject, $body);
        if (!$sent) {
            error_log("Failed to send password reset email to {$email}");
        }

        $csrfToken = $this->generateCsrfToken();
        $this->view('auth/forgot-password', [
            'csrfToken' => $csrfToken,
            'error' => '',
            'success' => $successMsg
        ]);
    }

    public function showResetForm(): void
    {
        $rawToken = $_GET['token'] ?? '';
        if (empty($rawToken)) {
            $this->redirect('/forgot-password');
            return;
        }

        $csrfToken = $this->generateCsrfToken();
        $this->view('auth/reset-password', [
            'csrfToken' => $csrfToken,
            'token' => $rawToken,
            'error' => ''
        ]);
    }

    public function resetPassword(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrfToken($token)) {
            http_response_code(419);
            die('CSRF token mismatch.');
        }

        $rawToken = $_POST['reset_token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        if (empty($rawToken)) {
            $this->redirect('/forgot-password');
            return;
        }

        $error = '';
        if (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $passwordConfirmation) {
            $error = 'Passwords do not match.';
        }

        if ($error) {
            $csrfToken = $this->generateCsrfToken();
            $this->view('auth/reset-password', [
                'csrfToken' => $csrfToken,
                'token' => $rawToken,
                'error' => $error
            ]);
            return;
        }

        $userModel = new User($this->db);
        $resetRecord = $userModel->findPasswordResetByRawToken($rawToken);

        if (!$resetRecord) {
            $csrfToken = $this->generateCsrfToken();
            $this->view('auth/reset-password', [
                'csrfToken' => $csrfToken,
                'token' => $rawToken,
                'error' => 'Invalid or expired token.'
            ]);
            return;
        }

        $createdAt = strtotime($resetRecord['created_at']);
        if (time() - $createdAt > 3600) {
            $userModel->deletePasswordResetByEmail($resetRecord['email']);
            $csrfToken = $this->generateCsrfToken();
            $this->view('auth/reset-password', [
                'csrfToken' => $csrfToken,
                'token' => $rawToken,
                'error' => 'Token has expired. Please request a new one.'
            ]);
            return;
        }

        $email = $resetRecord['email'];
        $user = $userModel->findByEmail($email);
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $userModel->updatePassword($user['id'], $newHash);
        $userModel->deletePasswordResetByEmail($email);

        $_SESSION['flash_message'] = 'Your password has been reset. Please log in.';
        $this->redirect('/login');
    }
}