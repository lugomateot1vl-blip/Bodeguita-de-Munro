<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function cliente_autenticado(): bool
{
    return !empty($_SESSION['cliente_id']);
}

function cliente_preparar_tabla(PDO $db): void
{
    // La tabla se crea al primer uso para evitar un paso manual de instalación.
    $db->exec("CREATE TABLE IF NOT EXISTS clientes (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(140) NOT NULL UNIQUE,
        telefono VARCHAR(40) NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function cliente_destino_seguro(?string $destino): string
{
    $permitidos = ['index.php', 'catalogo.php#mi-seleccion', 'catalogo.php', 'bodeguita.php', 'catas.php', 'visitanos.php'];
    return in_array($destino, $permitidos, true) ? $destino : 'catalogo.php#mi-seleccion';
}
