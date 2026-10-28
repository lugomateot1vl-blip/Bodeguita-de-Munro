<?php
require_once __DIR__ . '/app/config/Conexion.php';
require_once __DIR__ . '/app/dao/BebidaDAO.php';
require_once __DIR__ . '/app/helpers.php';
$destacadas = [];
try {
    $catalogo = (new BebidaDAO(Conexion::obtener()))->listarActivas();
    $destacadas = array_values(array_filter($catalogo, static fn(array $bebida): bool => !empty($bebida['destacada']) && (int)$bebida['stock'] > 0));
    if (count($destacadas) < 4) $destacadas = array_values(array_filter($catalogo, static fn(array $bebida): bool => (int)$bebida['stock'] > 0));
    $destacadas = array_slice($destacadas, 0, 4);
} catch (Throwable $error) {
    $destacadas = [];
}
$paginaActiva = 'inicio';
$tituloPagina = 'Inicio';
require __DIR__ . '/partials/header.php';
?>
<section class="home-hero shop-home-hero">
  <div class="hero-decoration decoration-one" aria-hidden="true"></div><div class="hero-decoration decoration-two" aria-hidden="true"></div>
  <div class="hero-copy">
    <p class="hero-eyebrow"><span>✳</span> LA BODEGUITA DE MUNRO · VINOS Y BEBIDAS</p>
    <h1>Encontrá tu próximo<br><em>favorito.</em></h1>
    <p class="hero-lead">Explorá etiquetas, compará precios y armá tu pedido en pocos pasos. Si necesitás una recomendación, estamos para ayudarte.</p>
    <div class="home-actions"><a class="hero-button" href="vinos.php#seleccion">Ver catálogo y precios <span>↗</span></a><a class="home-secondary-link" href="catas.php">Conocer las próximas catas</a></div>
    <form class="home-search" action="vinos.php" method="get"><label for="home-search-input">¿Ya sabés qué estás buscando?</label><div><input id="home-search-input" name="buscar" type="search" placeholder="Buscá por vino, bodega o región"><button type="submit" aria-label="Buscar en el catálogo">Buscar <span>⌕</span></button></div></form>
  </div>
  <div class="hero-illustration" aria-label="Ilustración de una botella y una copa de vino">
    <span class="illustration-burst burst-one">✳</span><span class="illustration-burst burst-two">✦</span>
    <div class="illustration-sticker">BUENOS VINOS<br><b>BUENOS MOMENTOS</b></div>
    <svg viewBox="0 0 440 410" role="img" aria-hidden="true">
      <ellipse cx="228" cy="358" rx="171" ry="25" fill="#37533a" opacity=".18"/><circle cx="235" cy="195" r="169" fill="#f8e4a7"/>
      <path d="M53 222c44-58 89-60 126-4s74 66 119 8 71-44 94-15v105H53z" fill="#ed7955"/>
      <path d="M104 339h242l-25 22H126z" fill="#34513b"/>
      <path d="M184 99h76v61c0 20 31 39 37 70l7 90c1 17-11 29-28 29h-93c-17 0-29-12-28-29l8-90c3-31 29-50 29-70z" fill="#31523b" stroke="#233d2c" stroke-width="7" stroke-linejoin="round"/>
      <path d="M184 101h76v48h-76z" fill="#e85d45"/><path d="M168 230h113l7 86c1 14-8 23-22 23h-83c-14 0-23-9-22-23z" fill="#ffe9b7"/>
      <path d="M184 256h80" stroke="#e37a54" stroke-width="6" stroke-linecap="round"/><text x="224" y="291" text-anchor="middle" font-family="Georgia,serif" font-size="27" font-weight="bold" fill="#34513b">BM</text>
      <path d="M324 190h45l-8 89c-2 20-12 32-24 36v38h25v9h-64v-9h25v-38c-12-4-22-16-24-36z" fill="#fff8e7" stroke="#34513b" stroke-width="6" stroke-linejoin="round"/>
      <path d="M301 237h67l-4 39h-60z" fill="#df664b"/><path d="M322 361h31" stroke="#34513b" stroke-width="7" stroke-linecap="round"/>
      <path d="M109 143l8 23m-20-11 24 0m-13-14v28" stroke="#34513b" stroke-width="5" stroke-linecap="round"/>
    </svg>
    <span class="illustration-caption">Una selección para cada mesa <small>Munro · Buenos Aires</small></span>
  </div>
</section>
<section class="shop-shortcuts" aria-labelledby="shortcut-title">
  <div class="home-section-heading"><div><p class="eyebrow">EMPEZÁ POR LO QUE TE GUSTA</p><h2 id="shortcut-title">¿Qué tenés ganas <em>de tomar?</em></h2></div><a href="vinos.php#seleccion">Ver todo el catálogo <span>↗</span></a></div>
  <div class="shortcut-grid">
    <a class="shortcut-card shortcut-red" href="vinos.php?tipo=tinto#seleccion"><span class="shortcut-symbol" aria-hidden="true">◉</span><span><strong>Vinos tintos</strong><small>Clásicos y etiquetas para descubrir</small></span><b aria-hidden="true">↗</b></a>
    <a class="shortcut-card shortcut-gold" href="vinos.php?tipo=blanco#seleccion"><span class="shortcut-symbol" aria-hidden="true">◌</span><span><strong>Blancos y rosados</strong><small>Frescos, frutales y livianos</small></span><b aria-hidden="true">↗</b></a>
    <a class="shortcut-card shortcut-green" href="vinos.php?tipo=espumante#seleccion"><span class="shortcut-symbol" aria-hidden="true">✳</span><span><strong>Espumantes</strong><small>Para brindar y celebrar</small></span><b aria-hidden="true">↗</b></a>
    <a class="shortcut-card shortcut-plum" href="vinos.php?tipo=otro#seleccion"><span class="shortcut-symbol" aria-hidden="true">＋</span><span><strong>Otras bebidas</strong><small>Encontrá algo distinto</small></span><b aria-hidden="true">↗</b></a>
  </div>
</section>
<?php if ($destacadas): ?>
<section class="home-featured" aria-labelledby="featured-title">
  <div class="home-section-heading"><div><p class="eyebrow">PARA ELEGIR SIN VUELTAS</p><h2 id="featured-title">Recomendados <em>de la casa.</em></h2></div><p>Consultá los precios y detalles de cada etiqueta en el catálogo.</p></div>
  <div class="featured-grid">
    <?php foreach ($destacadas as $i => $bebida): ?>
    <article class="featured-card">
      <a class="featured-visual featured-tone-<?= $i % 4 ?>" href="vinos.php?buscar=<?= rawurlencode($bebida['nombre']) ?>#seleccion" aria-label="Ver <?= h($bebida['nombre']) ?> en el catálogo">
        <?php if (!empty($bebida['imagen'])): ?><img src="<?= h($bebida['imagen']) ?>" alt="Botella de <?= h($bebida['nombre']) ?>" loading="lazy"><?php else: ?><span class="featured-bottle" aria-hidden="true"><i></i><b><?= h(mb_strtoupper(mb_substr($bebida['bodega'], 0, 1))) ?></b></span><?php endif; ?>
        <span class="featured-type"><?= h($bebida['variedad']) ?></span>
      </a>
      <div class="featured-copy"><p><?= h($bebida['bodega']) ?> · <?= h($bebida['region']) ?></p><h3><?= h($bebida['nombre']) ?></h3><div><strong><?= dinero((float)$bebida['precio']) ?></strong><a href="vinos.php?buscar=<?= rawurlencode($bebida['nombre']) ?>#seleccion">Ver detalle <span>↗</span></a></div></div>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
<section class="how-to-order" aria-labelledby="how-title">
  <div class="how-intro"><p class="eyebrow-light">FÁCIL Y CLARO</p><h2 id="how-title">Tu pedido, en <em>tres pasos.</em></h2><a href="vinos.php#seleccion" class="how-cta">Empezar a elegir <span>↗</span></a></div>
  <ol class="how-steps"><li><b>01</b><span><strong>Elegí tus bebidas</strong><small>Filtrá por tipo y revisá los precios.</small></span></li><li><b>02</b><span><strong>Sumalas a Mi selección</strong><small>El carrito te muestra el total estimado.</small></span></li><li><b>03</b><span><strong>Enviá el pedido</strong><small>La tienda confirma disponibilidad y entrega.</small></span></li></ol>
</section>
<section class="home-info-cards"><a href="bodeguita.php"><span>01 · NUESTRA CASA</span><strong>Conocé la Bodeguita</strong><small>Una vinoteca de barrio en Munro ↗</small></a><a href="catas.php"><span>02 · ENCUENTROS</span><strong>Catas y novedades</strong><small>Enterate de los próximos encuentros ↗</small></a><a href="visitanos.php"><span>03 · TE ESPERAMOS</span><strong>Cómo llegar</strong><small>Encontranos en Munro ↗</small></a></section>
<?php require __DIR__ . '/partials/footer.php'; ?>