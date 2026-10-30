<?php
declare(strict_types=1);
require_once __DIR__ . '/app/config/Conexion.php';
require_once __DIR__ . '/app/helpers/funciones.php';
require_once __DIR__ . '/app/dao/BebidaDAO.php';
require_once __DIR__ . '/partials/imagen-bebida.php';

$idVino = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$bebidas = (new BebidaDAO(Conexion::obtener()))->listarActivas();
$vino = null;
foreach ($bebidas as $bebida) {
    if ((int)$bebida['id'] === (int)$idVino) { $vino = $bebida; break; }
}

if (!$vino) {
    http_response_code(404);
    $paginaActiva = 'catalogo';
    $tituloPagina = 'Etiqueta no encontrada';
    require __DIR__ . '/partials/header.php';
    ?>
    <section class="wine-not-found page-hero"><p class="eyebrow">CATÁLOGO DE LA BODEGUITA</p><h1>No encontramos <em>esa etiqueta.</em></h1><p>Puede que ya no esté disponible o que el enlace haya cambiado.</p><a class="button button-wine" href="catalogo.php">Volver al catálogo ↗</a></section>
    <?php require __DIR__ . '/partials/footer.php'; exit;
}

$foto = imagenCatalogoBebida($vino);
$similares = array_slice(array_values(array_filter($bebidas, static fn(array $otro): bool => (int)$otro['id'] !== (int)$vino['id'] && mb_strtolower((string)$otro['variedad']) === mb_strtolower((string)$vino['variedad']))), 0, 3);
$paginaActiva = 'vino';
$tituloPagina = (string)$vino['nombre'];
require __DIR__ . '/partials/header.php';
?>
<nav class="wine-breadcrumb" aria-label="Migas de pan"><a href="index.php">Inicio</a><span aria-hidden="true">/</span><a href="catalogo.php">Catálogo</a><span aria-hidden="true">/</span><span><?= e($vino['nombre']) ?></span></nav>
<section class="wine-detail">
  <div class="wine-detail-photo">
    <img src="<?= e($foto['src']) ?>" alt="<?= $foto['ilustrativa'] ? 'Imagen ilustrativa de ' . e(mb_strtolower((string)$vino['variedad'])) : 'Botella de ' . e((string)$vino['nombre']) ?>" fetchpriority="high">
    <?php if ($foto['ilustrativa']): ?><span class="illustrative-label">Foto ilustrativa</span><?php endif; ?>
    <?php if ((int)$vino['destacada']): ?><span class="wine-detail-featured">RECOMENDADA POR LA CASA</span><?php endif; ?>
  </div>
  <div class="wine-detail-copy">
    <p class="eyebrow">UNA ETIQUETA PARA DESCUBRIR</p>
    <p class="wine-detail-type"><?= e($vino['variedad']) ?></p>
    <h1><?= e($vino['nombre']) ?></h1>
    <p class="wine-detail-maker"><?= e($vino['bodega']) ?></p>
    <p class="wine-detail-description"><?= e((string)($vino['descripcion'] ?: 'Una etiqueta de la selección de La Bodeguita de Munro. Consultanos si querés una recomendación para elegirla.')) ?></p>
    <div class="wine-facts" aria-label="Datos del vino">
      <div><span>Origen</span><strong><?= e($vino['region'] ?: 'Consultar en la tienda') ?></strong></div>
      <div><span>Variedad</span><strong><?= e($vino['variedad'] ?: 'Consultar en la tienda') ?></strong></div>
      <div><span>Añada</span><strong><?= !empty($vino['anio']) ? e((string)$vino['anio']) : 'Consultar en la tienda' ?></strong></div>
    </div>
    <section class="wine-purchase" aria-label="Comprar esta etiqueta">
      <div class="wine-purchase-price"><span>Precio de la tienda</span><strong><?= pesos((float)$vino['precio']) ?></strong></div>
      <p class="wine-availability"><?= (int)$vino['stock'] > 0 ? '<span class="stock-dot"></span> Disponible para tu pedido' : '<span class="stock-dot sold"></span> Consultanos por disponibilidad' ?></p>
      <?php if ((int)$vino['stock'] > 0): ?>
      <div class="wine-purchase-row"><label for="wine-quantity">Unidades</label><input id="wine-quantity" type="number" min="1" max="<?= (int)$vino['stock'] ?>" value="1" inputmode="numeric"><button class="button button-wine" id="add-wine" type="button" data-id="<?= (int)$vino['id'] ?>" data-name="<?= e($vino['nombre']) ?>" data-price="<?= e((string)$vino['precio']) ?>" data-stock="<?= (int)$vino['stock'] ?>">Sumar a mi selección <span aria-hidden="true">＋</span></button></div>
      <?php else: ?><button class="button button-unavailable" type="button" disabled>Sin stock por el momento</button><?php endif; ?>
      <p class="wine-purchase-note">La Bodeguita confirma disponibilidad, pago y entrega al recibir tu pedido.</p>
      <p class="wine-added" id="wine-added" role="status" aria-live="polite" hidden></p>
      <a class="wine-cart-link" href="catalogo.php#mi-seleccion">Ir a Mi selección ↗</a>
    </section>
  </div>
</section>
<?php if ($similares): ?>
<section class="related-wines"><div class="section-heading"><div><p class="eyebrow">MÁS DE ESTA VARIEDAD</p><h2>También te puede <em>gustar.</em></h2></div><a class="text-link" href="catalogo.php">Volver al catálogo ↗</a></div><div class="featured-grid">
  <?php foreach ($similares as $i => $otro): $fotoOtro = imagenCatalogoBebida($otro); ?>
  <article class="featured-card"><a class="featured-image featured-tone-<?= $i % 4 ?>" href="vino.php?id=<?= (int)$otro['id'] ?>"><img src="<?= e($fotoOtro['src']) ?>" alt="<?= $fotoOtro['ilustrativa'] ? 'Imagen ilustrativa de ' . e(mb_strtolower((string)$otro['variedad'])) : 'Botella de ' . e((string)$otro['nombre']) ?>" loading="lazy"><span class="featured-label"><?= e($otro['variedad']) ?></span></a><div class="featured-copy"><p><?= e($otro['bodega']) ?> · <?= e($otro['region']) ?></p><h3><a href="vino.php?id=<?= (int)$otro['id'] ?>"><?= e($otro['nombre']) ?></a></h3><div><strong><?= pesos((float)$otro['precio']) ?></strong><a href="vino.php?id=<?= (int)$otro['id'] ?>">Ver detalles ↗</a></div></div></article>
  <?php endforeach; ?>
</div></section>
<?php endif; ?>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script src="js/detalle-vino.js" defer></script>
<?php require __DIR__ . '/partials/footer.php'; ?>
