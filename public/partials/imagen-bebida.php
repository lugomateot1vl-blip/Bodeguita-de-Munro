<?php
/**
 * Devuelve una foto cargada por el administrador o una foto ilustrativa por variedad.
 * Las fotos de ejemplo nunca deben confundirse con la etiqueta exacta del producto.
 */
function imagenCatalogoBebida(array $bebida): array
{
    $imagenCargada = trim((string)($bebida['imagen'] ?? ''));
    if ($imagenCargada !== '') {
        return ['src' => $imagenCargada, 'ilustrativa' => false];
    }

    $variedad = mb_strtolower(trim((string)($bebida['variedad'] ?? '')));
    if (str_contains($variedad, 'tinto') || str_contains($variedad, 'malbec') || str_contains($variedad, 'cabernet') || str_contains($variedad, 'bonarda')) {
        $archivo = 'vino-tinto.jpg';
    } elseif (str_contains($variedad, 'blanco') || str_contains($variedad, 'chardonnay') || str_contains($variedad, 'torront')) {
        $archivo = 'vino-blanco.jpg';
    } elseif (str_contains($variedad, 'ros')) {
        $archivo = 'vino-rosado.jpg';
    } elseif (str_contains($variedad, 'espum') || str_contains($variedad, 'brut') || str_contains($variedad, 'champ')) {
        $archivo = 'vino-espumante.jpg';
    } else {
        $archivo = 'vino-botellas.jpg';
    }

    return ['src' => 'media/' . $archivo, 'ilustrativa' => true];
}
