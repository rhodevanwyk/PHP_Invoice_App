<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Models\Client;

class DashboardController extends Controller
{
    private Auth $auth;
    private Database $db;

    public function __construct(array $services)
    {
        $this->auth = $services['auth'];
        $this->db = $services['db'];
    }

    public function index(): void
    {
        if (!$this->auth->check()) {
            $this->redirect('/');
            return;
        }

        $clientModel = new Client($this->db);

        $this->view('dashboard', [
            'layout' => 'app',
            'user' => $this->auth->user(),
            'activeNav' => 'dashboard',
            'clientCount' => $clientModel->countForUser($this->auth->id()),
        ]);
    }
}
