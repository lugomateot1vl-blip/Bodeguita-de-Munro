<?php
require_once __DIR__ . '/cliente-auth.php';
$paginaActiva = $paginaActiva ?? 'inicio';
$tituloPagina = $tituloPagina ?? 'La Bodeguita de Munro';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f8e8a7">
  <meta name="description" content="Vinos y bebidas de La Bodeguita de Munro. Encontrá etiquetas, compará precios y armá tu pedido.">
  <title><?= e($tituloPagina) ?> · La Bodeguita de Munro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <script>try{document.documentElement.dataset.theme=localStorage.getItem('bodeguita-theme')==='dark'?'dark':'light'}catch(e){}</script>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css?v=20261028-6">
  <link rel="stylesheet" href="css/fotografia-vinos.css?v=20261028-3">
  <link rel="stylesheet" href="css/modo-oscuro.css?v=20261028-7">
  <link rel="stylesheet" href="css/ajustes-inicio.css?v=20261028-4">
  <?php if ($paginaActiva === 'vino'): ?><link rel="stylesheet" href="css/ficha-vino.css?v=3"><?php endif; ?>
  <?php if ($paginaActiva === 'cuenta'): ?><link rel="stylesheet" href="css/cuenta.css?v=20261028-6"><?php endif; ?>
</head>
<body>
<a class="skip-link" href="#contenido">Ir al contenido</a>
<div class="announcement"><span>VINOS, BEBIDAS Y BUENOS ENCUENTROS</span><i aria-hidden="true">✳</i><span>MUNRO · BUENOS AIRES</span></div>
<header class="site-header">
  <a class="wordmark" href="index.php" aria-label="La Bodeguita de Munro, inicio"><span>La Bodeguita <small>de Munro</small></span></a>
  <button class="menu-toggle" aria-expanded="false" aria-controls="primary-nav">Menú</button>
  <nav class="primary-nav" id="primary-nav" aria-label="Navegación principal">
    <a <?= in_array($paginaActiva, ['catalogo', 'vino'], true) ? 'aria-current="page"' : '' ?> href="catalogo.php">Catálogo</a>
    <a <?= $paginaActiva === 'bodeguita' ? 'aria-current="page"' : '' ?> href="bodeguita.php">La Bodeguita</a>
    <a <?= $paginaActiva === 'catas' ? 'aria-current="page"' : '' ?> href="catas.php">Catas</a>
    <a <?= $paginaActiva === 'visitanos' ? 'aria-current="page"' : '' ?> href="visitanos.php">Cómo llegar</a>
  </nav>
  <div class="header-actions">
    <?php if (cliente_autenticado()): ?><a class="enlace-cuenta" href="cuenta.php" aria-label="Mi cuenta, <?= e($_SESSION['cliente_nombre'] ?? '') ?>" title="Mi cuenta"><svg class="icono-cuenta" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M4.8 20a7.2 7.2 0 0 1 14.4 0"></path></svg></a><?php else: ?><a class="enlace-cuenta" href="cuenta.php?destino=catalogo.php%23mi-seleccion" aria-label="Iniciar sesión" title="Iniciar sesión"><svg class="icono-cuenta" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"></circle><path d="M4.8 20a7.2 7.2 0 0 1 14.4 0"></path></svg></a><?php endif; ?>
    <?php if ($paginaActiva === 'catalogo'): ?>
      <button class="selection-link" id="open-cart" aria-label="Abrir Mi selección">Mi selección <b id="cart-count">0</b></button>
    <?php else: ?>
      <a class="selection-link" href="catalogo.php#mi-seleccion" aria-label="Abrir Mi selección en el catálogo">Mi selección <b id="cart-count">0</b></a>
    <?php endif; ?>
    <button class="theme-toggle" id="theme-toggle" type="button" aria-label="Activar modo oscuro" aria-pressed="false" title="Cambiar tema"><span class="theme-toggle-icon" aria-hidden="true">☾</span></button>
  </div>
</header>
<main id="contenido">
