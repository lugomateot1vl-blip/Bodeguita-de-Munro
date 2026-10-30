<?php
declare(strict_types=1);

final class BebidaDAO
{
    public function __construct(private PDO $db) {}

    /** Lista solo las bebidas publicadas para la tienda. */
    public function listarActivas(): array
    {
        return $this->db->query('SELECT * FROM bebidas WHERE activa = 1 ORDER BY destacada DESC, nombre')->fetchAll();
    }

    /** Devuelve todo el inventario para la administración. */
    public function listarTodas(): array
    {
        return $this->db->query('SELECT * FROM bebidas ORDER BY creado_en DESC')->fetchAll();
    }

    public function guardar(array $datos, ?int $id = null): void
    {
        if ($id) {
            $sql = 'UPDATE bebidas SET nombre=?, bodega=?, variedad=?, region=?, anio=?, precio=?, stock=?, imagen=?, descripcion=?, activa=?, destacada=? WHERE id=?';
            $this->db->prepare($sql)->execute([...$datos, $id]);
            return;
        }
        $sql = 'INSERT INTO bebidas (nombre,bodega,variedad,region,anio,precio,stock,imagen,descripcion,activa,destacada) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
        $this->db->prepare($sql)->execute($datos);
    }

    public function cambiarEstado(int $id, int $activo): void
    {
        $this->db->prepare('UPDATE bebidas SET activa=? WHERE id=?')->execute([$activo, $id]);
    }
}
