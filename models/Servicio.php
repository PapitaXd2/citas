<?php

class Servicio
{
    public function __construct(private PDO $db) {}

    // El cliente solo ve los servicios activos; el administrador ve todos
    public function todos(bool $soloActivos): array
    {
        $sql = 'SELECT * FROM servicios'
             . ($soloActivos ? ' WHERE activo = TRUE' : '')
             . ' ORDER BY nombre';
        return $this->db->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM servicios WHERE id = ?');
        $consulta->execute([$id]);
        return $consulta->fetch() ?: null;
    }

    public function crear(array $d): void
    {
        $sql = 'INSERT INTO servicios (nombre, descripcion, duracion, precio, imagen, activo)
                VALUES (?, ?, ?, ?, ?, ?)';
        $this->db->prepare($sql)->execute([
            $d['nombre'], $d['descripcion'], $d['duracion'], $d['precio'], $d['imagen'],
            $d['activo'] ? 'true' : 'false',   // PostgreSQL espera true o false
        ]);
    }

    // Si no se sube imagen nueva, COALESCE conserva la anterior
    public function actualizar(int $id, array $d): void
    {
        $sql = 'UPDATE servicios SET
                    nombre = ?, descripcion = ?, duracion = ?, precio = ?,
                    imagen = COALESCE(?, imagen),
                    activo = CAST(? AS BOOLEAN)
                WHERE id = ?';
        $this->db->prepare($sql)->execute([
            $d['nombre'], $d['descripcion'], $d['duracion'], $d['precio'], $d['imagen'],
            $d['activo'] ? 'true' : 'false', $id,
        ]);
    }

    public function eliminar(int $id): void
    {
        $this->db->prepare('DELETE FROM servicios WHERE id = ?')->execute([$id]);
    }

    public function desactivar(int $id): void
    {
        $this->db->prepare('UPDATE servicios SET activo = FALSE WHERE id = ?')->execute([$id]);
    }
}
