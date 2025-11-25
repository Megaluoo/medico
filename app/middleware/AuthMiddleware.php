<?php
require_once __DIR__ . '/../helpers.php';

function requireAuth(): void
{
    if (!currentUser()) {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '/';
        redirect('/login');
    }
}

function requireRole($roles): void
{
    requireAuth();
    $user = currentUser();
    $roles = (array) $roles;

    if (!$user || !in_array($user['role'] ?? null, $roles, true)) {
        http_response_code(403);
        echo 'Acceso denegado';
        exit;
    }
}
