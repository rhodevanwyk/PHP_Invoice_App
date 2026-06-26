<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Client;

class ClientController extends Controller
{
    private Database $db;
    private Auth $auth;

    public function __construct(array $services)
    {
        $this->db   = $services['db'];
        $this->auth = $services['auth'];
    }

    private function requireAuth(): void
    {
        if (!$this->auth->check()) {
            $this->redirect('/login');
            exit;
        }
    }

    private function appView(string $template, array $data = []): void
    {
        $this->view($template, array_merge([
            'layout' => 'app',
            'user' => $this->auth->user(),
            'activeNav' => 'clients',
        ], $data));
    }

    public function index(): void
    {
        $this->requireAuth();

        $page = max(1, (int)($_GET['page'] ?? 1));
        $clientModel = new Client($this->db);
        $clients = $clientModel->allForUser($this->auth->id(), $page);

        $this->appView('clients/index', [
            'clients' => $clients['data'],
            'pagination' => $clients,
            'flash' => $this->pullFlash(),
        ]);
    }

    public function showCreate(): void
    {
        $this->requireAuth();
        $csrfToken = $this->generateCsrfToken();
        $this->appView('clients/create', ['csrfToken' => $csrfToken, 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        $this->requireAuth();

        $token = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrfToken($token)) {
            http_response_code(419);
            die('CSRF token mismatch.');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $errors = [];
        if ($name === '') $errors['name'] = 'Client name is required.';
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email required.';
        }

        if (!empty($errors)) {
            $csrfToken = $this->generateCsrfToken();
            $this->appView('clients/create', ['csrfToken' => $csrfToken, 'errors' => $errors, 'old' => $_POST]);
            return;
        }

        $clientModel = new Client($this->db);
        $clientModel->create($this->auth->id(), [
            'name' => $name,
            'email' => $email ?: null,
            'address' => $address ?: null,
            'phone' => $phone ?: null,
        ]);

        $this->flash('success', 'Client created successfully.');
        $this->redirect('/clients');
    }

    public function showEdit(array $params): void
    {
        $this->requireAuth();

        $id = (int)($params['id'] ?? 0);
        $clientModel = new Client($this->db);
        $client = $clientModel->findByIdAndUser($id, $this->auth->id());

        if (!$client) {
            http_response_code(404);
            die('Client not found.');
        }

        $csrfToken = $this->generateCsrfToken();
        $this->appView('clients/edit', [
            'csrfToken' => $csrfToken,
            'client' => $client,
            'errors' => [],
            'old' => $client,
        ]);
    }

    public function update(array $params): void
    {
        $this->requireAuth();

        $id = (int)($params['id'] ?? 0);
        $clientModel = new Client($this->db);
        $client = $clientModel->findByIdAndUser($id, $this->auth->id());

        if (!$client) {
            http_response_code(404);
            die('Client not found.');
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!$this->validateCsrfToken($token)) {
            http_response_code(419);
            die('CSRF token mismatch.');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $errors = [];
        if ($name === '') $errors['name'] = 'Client name is required.';
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email required.';
        }

        if (!empty($errors)) {
            $csrfToken = $this->generateCsrfToken();
            $this->appView('clients/edit', [
                'csrfToken' => $csrfToken,
                'client' => $client,
                'errors' => $errors,
                'old' => ['name' => $name, 'email' => $email, 'address' => $address, 'phone' => $phone],
            ]);
            return;
        }

        $clientModel->update($id, $this->auth->id(), [
            'name' => $name,
            'email' => $email ?: null,
            'address' => $address ?: null,
            'phone' => $phone ?: null,
        ]);

        $this->flash('success', 'Client updated successfully.');
        $this->redirect('/clients');
    }

    public function destroy(array $params): void
    {
        $this->requireAuth();

        $id = (int)($params['id'] ?? 0);
        $clientModel = new Client($this->db);
        $client = $clientModel->findByIdAndUser($id, $this->auth->id());

        if (!$client) {
            http_response_code(404);
            die('Client not found.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($token)) {
                http_response_code(419);
                die('CSRF token mismatch.');
            }

            if ($clientModel->hasInvoices($id, $this->auth->id())) {
                $this->flash('error', 'Cannot delete a client that has invoices. Remove or reassign their invoices first.');
                $this->redirect('/clients');
            }

            $clientModel->delete($id, $this->auth->id());
            $this->flash('success', 'Client deleted successfully.');
            $this->redirect('/clients');
        }

        $csrfToken = $this->generateCsrfToken();
        $this->appView('clients/delete', [
            'csrfToken' => $csrfToken,
            'client' => $client,
            'hasInvoices' => $clientModel->hasInvoices($id, $this->auth->id()),
        ]);
    }
}