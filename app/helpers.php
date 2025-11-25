<?php
function view(string $template, array $data = []): void
{
    extract($data);
    include __DIR__ . '/../views/layout.php';
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}
