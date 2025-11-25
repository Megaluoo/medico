<?php
class Auth
{
    private const SESSION_KEY = 'user';

    public static function user(): ?array
    {
        return Session::get(self::SESSION_KEY);
    }

    public static function check(): bool
    {
        return !is_null(self::user());
    }

    public static function login(array $user): void
    {
        Session::set(self::SESSION_KEY, $user);
    }

    public static function logout(): void
    {
        Session::remove(self::SESSION_KEY);
    }
}
