<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e(?string $valor): string
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function validar_csrf(): void
{
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('La sesión del formulario venció. Volvé atrás y probá de nuevo.');
    }
}

function es_admin(): bool
{
    return !empty($_SESSION['admin_id']);
}

function requerir_admin(): void
{
    if (!es_admin()) {
        header('Location: admin.php');
        exit;
    }
}

function pesos(float $valor): string
{
    return '$ ' . number_format($valor, 0, ',', '.');
}
