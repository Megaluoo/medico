<?php
class User
{
    private array $data;

    public function __construct()
    {
        $file = __DIR__ . '/../../data/users.json';
        $json = file_get_contents($file);
        $this->data = json_decode($json, true) ?? [];
    }

    public function findByEmail(string $email): ?array
    {
        foreach ($this->data as $user) {
            if (strcasecmp($user['email'], $email) === 0) {
                return $user;
            }
        }

        return null;
    }

    public function verifyPassword(array $user, string $password): bool
    {
        return password_verify($password, $user['password'] ?? '');
    }
}
