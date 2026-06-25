<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

class DashboardController extends Controller
{
    private Auth $auth;

    public function __construct(array $services)
    {
        $this->auth = $services['auth'];
    }

    public function index(): void
    {
        if (!$this->auth->check()) {
            $this->redirect('/');
            return;
        }

        $this->view('dashboard', ['user' => $this->auth->user()]);
    }
}