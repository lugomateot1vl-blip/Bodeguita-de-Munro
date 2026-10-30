<?php
declare(strict_types=1);
require_once __DIR__ . '/helpers/funciones.php';
function h(?string $value): string { return e($value); }
function csrf(): string { return csrf_token(); }
function check_csrf(): void { validar_csrf(); }
function dinero(float $value): string { return pesos($value); }