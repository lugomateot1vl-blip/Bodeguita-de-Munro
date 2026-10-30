# La Bodeguita de Munro

Sitio PHP para una vinoteca de barrio: catálogo de bebidas, selección de productos, recepción de pedidos y administración del inventario.

## Requisitos

- XAMPP con Apache y MySQL/MariaDB.
- PHP 8.1 o superior, con PDO MySQL habilitado.

## Instalación local

1. Colocá la carpeta del repositorio en `C:\xampp\htdocs\Bodeguita De Munro`.
2. Iniciá Apache y MySQL desde XAMPP.
3. Importá `../database/bodeguita_munro.sql` con phpMyAdmin. Crea la base `bodeguita_munro` y agrega bebidas de ejemplo.
4. Si tu usuario de MySQL no es el predeterminado de XAMPP, ajustá `../app/config/Conexion.php` o configurá las variables `BODEGUITA_DB_HOST`, `BODEGUITA_DB_NAME`, `BODEGUITA_DB_USER` y `BODEGUITA_DB_PASS`.
5. Visitá `http://localhost/Bodeguita%20De%20Munro/public/`.
6. El enlace de administración abre `admin.php`. En la primera visita, creá un usuario y una contraseña privada de al menos 10 caracteres.

Los productos, precios y existencias iniciales son de muestra; actualizalos desde el panel antes de usar el sitio con inventario real.

## Apartados del sitio

- `index.php`: bienvenida y navegación a los apartados.
- `catalogo.php`: catálogo, filtros, búsqueda y selección de productos.
- `bodeguita.php`: presentación del local.
- `catas.php`: información sobre catas y encuentros.
- `visitanos.php`: ubicación y acceso al mapa.
- `pedido.php`: validación de stock y registro de pedidos.
- `admin.php`: acceso y panel para administrar bebidas y pedidos.

El carrito se conserva en el navegador. Al confirmar, el servidor valida el stock y registra una solicitud pendiente. El comercio coordina pago y entrega con el cliente; no se cobra en línea ni se integra una pasarela de pagos.

## Organización

```text
app/                       Conexión, funciones y consultas
database/                  Base de datos de instalación
partials/                  Archivos compartidos de la estructura original
public/                    Sitio web, páginas y recursos estáticos
```

Las imágenes de referencia se usan para inspirar la estética y mostrar el local. Confirmá los permisos de uso antes de publicar el sitio abiertamente.