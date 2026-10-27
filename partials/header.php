<?php
$paginaActiva = $paginaActiva ?? 'inicio';
$tituloPagina = $tituloPagina ?? 'La Bodeguita de Munro';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f5ead6">
  <title><?= htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') ?> · La Bodeguita de Munro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="public/css/sitio-estilos.css"><link rel="stylesheet" href="public/css/sitio-redisenio.css"><link rel="stylesheet" href="public/css/sitio-paginas.css"><?php if(in_array($paginaActiva,['admin','pedido'],true)): ?><link rel="stylesheet" href="public/css/sitio-admin.css"><?php endif; ?>
  <link rel="stylesheet" href="public/css/sitio-ajustes-finales.css">
</head>
<body>
<a class="skip" href="#contenido">Saltar al contenido</a>
<div class="topline">UNA BODEGA DE BARRIO <span>✳</span> MUNRO · BUENOS AIRES</div>
<header class="header">
  <a class="brand" href="index.php" aria-label="La Bodeguita de Munro, inicio"><span class="brand-name">Bodeguita de Munro</span></a>
  <button class="menu" aria-expanded="false" aria-controls="navigation">Menú</button>
  <nav id="navigation" aria-label="Navegación principal">
    <a <?= $paginaActiva==='vinos'?'aria-current="page"':'' ?> href="vinos.php">Catálogo</a>
    <a <?= $paginaActiva==='bodeguita'?'aria-current="page"':'' ?> href="bodeguita.php">La Bodeguita</a>
    <a <?= $paginaActiva==='catas'?'aria-current="page"':'' ?> href="catas.php">Catas</a>
    <a <?= $paginaActiva==='visita'?'aria-current="page"':'' ?> href="visitanos.php">Cómo llegar</a>
  </nav>
  <?php if ($paginaActiva==='vinos'): ?><button class="basket" id="basket-open" aria-label="Abrir mi selección">Mi selección <b id="basket-count">0</b></button><?php else: ?><a class="basket basket-link" href="vinos.php#mi-seleccion" aria-label="Abrir mi selección en el catálogo">Mi selección <b id="basket-count">0</b></a><?php endif; ?>
</header>
<main id="contenido">
