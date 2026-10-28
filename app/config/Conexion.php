<?php
declare(strict_types=1);

final class Conexion
{
    private static ?PDO $conexion = null;

    public static function obtener(): PDO
    {
        if (self::$conexion === null) {
            $host = getenv('BODEGUITA_DB_HOST') ?: 'localhost';
            $db = getenv('BODEGUITA_DB_NAME') ?: 'bodeguita_munro';
            $user = getenv('BODEGUITA_DB_USER') ?: 'root';
            $pass = getenv('BODEGUITA_DB_PASS') ?: '';
            self::$conexion = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$conexion;
    }
}
