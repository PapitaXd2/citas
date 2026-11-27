<?php

class Cita
{
    public function __construct(private PDO $db) {}

    // Horario del negocio: de 9:00 a 17:30, un turno cada 30 minutos
    public static function horariosDelDia(): array
    {
        $horas = [];
        for ($minutos = 9 * 60; $minutos <= 17 * 60 + 30; $minutos += 30) {
            $horas[] = sprintf('%02d:%02d', intdiv($minutos, 60), $minutos % 60);
        }
        return $horas;
    }

    // Fecha con formato AAAA-MM-DD, de hoy a 30 días, y que no sea domingo
    public static function fechaValida(string $fecha): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return false;
        }
        [$anio, $mes, $dia] = array_map('intval', explode('-', $fecha));
        if (!checkdate($mes, $dia, $anio)) {
            return false;
        }
        $hoy = date('Y-m-d');
        $limite = date('Y-m-d', strtotime('+30 days'));
        $esDomingo = date('w', strtotime($fecha)) === '0';
        return $fecha >= $hoy && $fecha <= $limite && !$esDomingo;
    }

    // Horarios libres de un día: todos menos los ocupados y, si es hoy, menos los que ya pasaron
    public function horasDisponibles(string $fecha): array
    {
        $sql = "SELECT to_char(hora, 'HH24:MI') FROM citas
                WHERE fecha = ? AND estado <> 'cancelada'";
        $consulta = $this->db->prepare($sql);
        $consulta->execute([$fecha]);
        $ocupadas = $consulta->fetchAll(PDO::FETCH_COLUMN);

        $libres = array_diff(self::horariosDelDia(), $ocupadas);
        if ($fecha === date('Y-m-d')) {
            $ahora = date('H:i');
            $libres = array_filter($libres, fn($hora) => $hora > $ahora);
        }
        return array_values($libres);
    }

    public function crear(int $usuarioId, int $servicioId, string $fecha, string $hora, string $notas): void
    {
        $sql = 'INSERT INTO citas (usuario_id, servicio_id, fecha, hora, notas) VALUES (?, ?, ?, ?, ?)';
        $this->db->prepare($sql)->execute([
            $usuarioId, $servicioId, $fecha, $hora, $notas === '' ? null : $notas,
        ]);
    }

    // Citas de un cliente, las más recientes primero (JOIN con servicios)
    public function delUsuario(int $usuarioId): array
    {
        $sql = "SELECT c.id, c.fecha, to_char(c.hora, 'HH24:MI') AS hora, c.estado,
                       s.nombre AS servicio, s.precio
                FROM citas c
                JOIN servicios s ON s.id = c.servicio_id
                WHERE c.usuario_id = ?
                ORDER BY c.fecha DESC, c.hora DESC";
        $consulta = $this->db->prepare($sql);
        $consulta->execute([$usuarioId]);
        return $consulta->fetchAll();
    }

    // Agenda del administrador: citas desde hoy, con datos del cliente y del servicio
    public function agenda(string $desde): array
    {
        $sql = "SELECT c.id, c.fecha, to_char(c.hora, 'HH24:MI') AS hora, c.estado, c.notas,
                       s.nombre AS servicio, u.nombre AS cliente, u.telefono
                FROM citas c
                JOIN servicios s ON s.id = c.servicio_id
                JOIN usuarios u ON u.id = c.usuario_id
                WHERE c.fecha >= ?
                ORDER BY c.fecha, c.hora";
        $consulta = $this->db->prepare($sql);
        $consulta->execute([$desde]);
        return $consulta->fetchAll();
    }

    // El cliente solo puede cancelar sus propias citas que no han pasado
    public function cancelarDeUsuario(int $citaId, int $usuarioId): void
    {
        $sql = "UPDATE citas SET estado = 'cancelada'
                WHERE id = ? AND usuario_id = ? AND fecha >= CURRENT_DATE";
        $this->db->prepare($sql)->execute([$citaId, $usuarioId]);
    }

    public function cambiarEstado(int $citaId, string $estado): void
    {
        $this->db->prepare('UPDATE citas SET estado = ? WHERE id = ?')->execute([$estado, $citaId]);
    }
}
