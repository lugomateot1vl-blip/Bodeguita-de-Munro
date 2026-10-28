<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/config/Conexion.php';
require_once __DIR__ . '/../app/helpers/funciones.php';
require_once __DIR__ . '/../app/dao/BebidaDAO.php';
require_once __DIR__ . '/partials/imagen-bebida.php';
$destacadas = [];
try {
    $bebidas = (new BebidaDAO(Conexion::obtener()))->listarActivas();
    $destacadas = array_values(array_filter($bebidas, static fn(array $bebida): bool => !empty($bebida['destacada']) && (int)$bebida['stock'] > 0));
    if (count($destacadas) < 4) $destacadas = array_values(array_filter($bebidas, static fn(array $bebida): bool => (int)$bebida['stock'] > 0));
    $destacadas = array_slice($destacadas, 0, 4);
} catch (Throwable $error) {
    $destacadas = [];
}
$paginaActiva = 'inicio';
$tituloPagina = 'Vinos para compartir';
require __DIR__ . '/partials/header.php';
?>
<section class="home-hero">
  <div class="hero-copy">
    <p class="eyebrow"><span>✳</span> UNA VINOTECA DE BARRIO · MUNRO</p>
    <h1>¡Bienvenido a<br><em>la Bodeguita!</em></h1>
    <p class="hero-lead">Qué bueno tenerte por acá. Somos una vinoteca de barrio en Munro, con vinos para descubrir, recomendaciones sin vueltas y buenos momentos para compartir.</p>
    <a class="button button-wine" href="bodeguita.php">Conocé nuestra casa <span aria-hidden="true">↗</span></a>
    <div class="hero-points"><span><b>01</b> Elegí tu etiqueta</span><span><b>02</b> Mirá el precio</span><span><b>03</b> Armá tu pedido</span></div>
  </div>
  <div class="hero-photo">
    <img src="media/estanterias-vino.png" alt="Estantes llenos de vinos y bebidas en La Bodeguita de Munro">
    <span class="photo-note">Hay una botella<br>para cada encuentro <i>✳</i></span>
    <span class="photo-tag">LA BODEGUITA · MUNRO</span>
  </div>
  <div class="hero-bottom-note">VINOS <span>✳</span> SOBREMESA <span>✳</span> BARRIO <span>✳</span> BUENOS MOMENTOS</div>
</section>
<section class="quick-find" aria-label="Buscar bebidas">
  <form action="catalogo.php" method="get" class="quick-search"><label for="home-search">¿Ya sabés qué estás buscando?</label><div><span aria-hidden="true">⌕</span><input id="home-search" type="search" name="buscar" placeholder="Buscá por vino, bodega o región"><button type="submit">Buscar en el catálogo <b>↗</b></button></div></form>
  <a class="quick-cata" href="catas.php"><span class="quick-cata-icon">✳</span><span><small>PLAN DISTINTO</small><strong>¿Cuándo es la próxima cata?</strong></span><b>↗</b></a>
</section>
<section class="category-section">
  <div class="section-heading"><div><p class="eyebrow">ENCONTRÁ TU ESTILO</p><h2>¿Qué tenés ganas <em>de tomar?</em></h2></div><a class="text-link" href="catalogo.php">Ver todo el catálogo ↗</a></div>
  <div class="category-grid">
    <a class="category-card category-red" href="catalogo.php?tipo=tinto#seleccion"><span class="category-number">01</span><img class="category-art-img" src="media/vino-tinto.jpg" alt="" loading="lazy"><strong>Tintos</strong><small>Con cuerpo, fruta y sobremesa</small><b>Explorar tintos ↗</b></a>
    <a class="category-card category-yellow" href="catalogo.php?tipo=blancos-y-rosados#seleccion"><span class="category-number">02</span><img class="category-art-img" src="media/vino-blanco.jpg" alt="" loading="lazy"><strong>Blancos y rosados</strong><small>Frescos, livianos y para compartir</small><b>Explorar blancos ↗</b></a>
    <a class="category-card category-green" href="catalogo.php?tipo=espumante#seleccion"><span class="category-number">03</span><img class="category-art-img" src="media/vino-espumante.jpg" alt="" loading="lazy"><strong>Espumantes</strong><small>Una buena excusa para brindar</small><b>Explorar espumantes ↗</b></a>
    <a class="category-card category-cream" href="catalogo.php?tipo=otro#seleccion"><span class="category-number">04</span><img class="category-art-img" src="media/vino-botellas.jpg" alt="" loading="lazy"><strong>Otras bebidas</strong><small>Encontrá algo distinto</small><b>Ver opciones ↗</b></a>
  </div>
</section>
<?php if ($destacadas): ?>
<section class="featured-section">
  <div class="section-heading"><div><p class="eyebrow">DE LA CASA PARA TU MESA</p><h2>Etiquetas que <em>nos gustan.</em></h2></div><p>Precios y disponibilidad según el catálogo de la tienda.</p></div>
  <div class="featured-grid">
    <?php foreach ($destacadas as $i => $vino): ?>
    <article class="featured-card">
      <a class="featured-image featured-tone-<?= $i % 4 ?>" href="vino.php?id=<?= (int)$vino['id'] ?>" aria-label="Ver detalles de <?= e($vino['nombre']) ?>">
        <?php $foto = imagenCatalogoBebida($vino); ?><img src="<?= e($foto['src']) ?>" alt="<?= $foto['ilustrativa'] ? 'Imagen ilustrativa de ' . e(mb_strtolower($vino['variedad'])) : 'Botella de ' . e($vino['nombre']) ?>" loading="lazy"><?php if ($foto['ilustrativa']): ?><span class="illustrative-label">Foto ilustrativa</span><?php endif; ?>
        <span class="featured-label"><?= e($vino['variedad']) ?></span><?php if ((int)$vino['destacada']): ?><span class="featured-badge">Recomendada</span><?php endif; ?>
      </a>
      <div class="featured-copy"><p><?= e($vino['bodega']) ?> · <?= e($vino['region']) ?></p><h3><a href="vino.php?id=<?= (int)$vino['id'] ?>"><?= e($vino['nombre']) ?></a></h3><div><strong><?= pesos((float)$vino['precio']) ?></strong><a href="vino.php?id=<?= (int)$vino['id'] ?>">Ver detalles ↗</a></div></div>
    </article>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
<section class="story-band">
  <div class="story-photo"><img src="media/bodega-interior.png" alt="Estanterías de La Bodeguita llenas de botellas" loading="lazy"><span>UNA CASA LLENA DE HISTORIAS</span></div>
  <div class="story-copy"><p class="eyebrow">DEL BARRIO, PARA TU MESA</p><h2>El vino se elige.<br>La charla <em>aparece sola.</em></h2><p>Nos gustan las recomendaciones sin vueltas, las etiquetas con algo para contar y ese momento en que alguien pregunta “¿qué llevo?”.</p><a class="button button-outline" href="bodeguita.php">Conocé la Bodeguita ↗</a></div>
</section>
<section class="tasting-band"><div class="tasting-copy"><p class="eyebrow">UNA VEZ CADA TANTO</p><h2>Una copa nueva,<br>una historia <em>para compartir.</em></h2><p>En la Bodeguita también organizamos catas. Consultanos por las próximas fechas y cómo reservar.</p><a class="button button-wine" href="catas.php">Ver información de catas ↗</a></div><div class="tasting-image"><img src="media/vino-cata.jpg" alt="Varias botellas de vino listas para compartir" loading="lazy"><span>DESCUBRIR · PROBAR · COMPARTIR</span></div></section>
<section class="visit-strip"><div><p class="eyebrow">ESTAMOS EN MUNRO</p><h2>Pasá a saludarnos <em>cuando quieras.</em></h2><p>Consultá cómo llegar y encontrá la tienda en el mapa.</p></div><a class="button button-light" href="visitanos.php">Cómo llegar <span>↗</span></a></section>
<?php require __DIR__ . '/partials/footer.php'; ?>
