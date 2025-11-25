<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers.php';

class AuthController
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function login(): void
    {
        $errors = [];
        $email = $_POST['email'] ?? '';

        if (isPost()) {
            $password = $_POST['password'] ?? '';

            $user = $this->users->findByEmail($email);
            if (!$user || !$this->users->verifyPassword($user, $password)) {
                $errors[] = 'Credenciales inválidas. Intenta nuevamente.';
            }

            if (empty($errors)) {
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ];

                $destination = $_SESSION['intended_url'] ?? '/';
                unset($_SESSION['intended_url']);
                redirect($destination);
            }
        }

        view('pages/login.php', [
            'title' => 'Ingresar',
            'errors' => $errors,
            'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
        ]);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_destroy();
        }

        redirect('/login');
    }
}
