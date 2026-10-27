# La Bodeguita de Munro

Sitio web adaptable para presentar una vinoteca de barrio, explorar etiquetas y enviar pedidos para confirmación. La tienda cuenta con un panel privado para administrar bebidas, stock y estados de pedido.

## Tecnologías

- PHP 8.2 y PDO
- MySQL / MariaDB
- HTML, CSS y JavaScript sin dependencias de compilación

## Instalación local con XAMPP

1. Copiá esta carpeta dentro de `C:\xampp\htdocs` o configurá Apache para servir la carpeta `public/`.
2. Iniciá Apache y MySQL desde XAMPP.
3. Importá `database/bodeguita_munro.sql` en phpMyAdmin. El archivo crea la base `bodeguita_munro` y agrega bebidas de ejemplo para mostrar el catálogo. Reemplazá esos nombres, descripciones, precios y existencias por el inventario real antes de publicar.
4. Revisá las credenciales MySQL en `app/config/Conexion.php` o definí `BODEGUITA_DB_HOST`, `BODEGUITA_DB_NAME`, `BODEGUITA_DB_USER` y `BODEGUITA_DB_PASS` en el entorno de PHP.
5. Abrí `http://localhost/Bodeguita%20De%20Munro/public/` (o la ruta que uses en `htdocs`).
6. Entrá a `public/admin.php`. En la primera visita, creá un usuario y una contraseña privada de al menos 10 caracteres.

## Recorridos

- **Tienda:** categorías y búsqueda, carrito que se conserva en el navegador, formulario de pedido y validación de stock al confirmar la solicitud.
- **Administración:** alta y edición de bebidas, precio, inventario, variedad, foto por URL, visibilidad y etiquetas recomendadas; revisión de pedidos y actualización de su estado.
- **Catas:** sección de presentación para consultar próximas fechas. No se publican fechas inventadas.

El checkout registra una solicitud pendiente y descuenta las unidades reservadas del stock. La tienda debe confirmar disponibilidad, medio de pago y entrega con la persona que realizó el pedido. No se integra una pasarela de pagos ni se cobra en línea.

## Organización

```text
app/
  config/Conexion.php       Conexión PDO centralizada
  dao/                      Lectura y escritura de bebidas y pedidos
  helpers/funciones.php     Sesión, permisos, CSRF y formato de precios
database/
  bodeguita_munro.sql       Esquema y catálogo de muestra
public/
  index.php                 Tienda y catálogo
  pedido.php                Validación y alta de solicitudes
  admin.php                 Acceso y panel de gestión
  css/                      Estilos de tienda y administración
  js/                       Filtros, carrito y formularios
  media/                    Fotografías de referencia del local
```

El proyecto usa imágenes de referencia compartidas para inspirar la estética y mostrar el local. Conservá el permiso de uso correspondiente si las publicás en un sitio abierto.
