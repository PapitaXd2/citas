<?php
class CitaController extends Controller
{
    private Cita $citas;
    private Servicio $servicios;

    public function __construct(PDO $db)
    {
        $this->citas = new Cita($db);
        $this->servicios = new Servicio($db);
    }

    public function agendar(): void
    {
        $usuario = $this->exigirSesion();
        $servicios = $this->servicios->todos(true);
        $error = null;
        $datos = [
            'servicio_id' => (int) ($_GET['servicio'] ?? 0),   // viene del botón "Agendar" del catálogo
            'fecha' => '',
            'hora' => '',
            'notas' => '',
        ];

        if ($this->esPost()) {
            $datos = [
                'servicio_id' => (int) ($_POST['servicio_id'] ?? 0),
                'fecha' => $_POST['fecha'] ?? '',
                'hora' => $_POST['hora'] ?? '',
                'notas' => trim($_POST['notas'] ?? ''),
            ];

            try {
                $this->validarCita($datos);
                $this->citas->crear($usuario['id'], $datos['servicio_id'], $datos['fecha'], $datos['hora'], $datos['notas']);
                $this->redirigir('mis-citas', 'Cita agendada. ¡Te esperamos!');
            } catch (InvalidArgumentException $ex) {
                $error = $ex->getMessage();
            } catch (PDOException $ex) {
                // 23505: el índice único rechazó la cita porque alguien ganó el horario
                if ($ex->getCode() !== '23505') {
                    throw $ex;
                }
                $error = 'Alguien acaba de ocupar ese horario. Elige otro.';
            }
        }

        $hoy = date('Y-m-d');
        $limite = date('Y-m-d', strtotime('+30 days'));
        require __DIR__ . '/../views/citas/agendar.php';
    }

    private function validarCita(array $d): void
    {
        $servicio = $this->servicios->buscarPorId($d['servicio_id']);
        if (!$servicio || !$servicio['activo']) {
            throw new InvalidArgumentException('Elige un servicio de la lista.');
        }
        if (!Cita::fechaValida($d['fecha'])) {
            throw new InvalidArgumentException('Elige una fecha de lunes a sábado dentro de los próximos 30 días.');
        }
        if (!in_array($d['hora'], $this->citas->horasDisponibles($d['fecha']), true)) {
            throw new InvalidArgumentException('Ese horario no está disponible. Elige otro.');
        }
        if (strlen($d['notas']) > 300) {
            throw new InvalidArgumentException('Las notas pueden tener máximo 300 caracteres.');
        }
    }

    public function misCitas(): void
    {
        $usuario = $this->exigirSesion();
        $citas = $this->citas->delUsuario($usuario['id']);
        $hoy = date('Y-m-d');
        require __DIR__ . '/../views/citas/mis_citas.php';
    }

    public function cancelar(): void
    {
        $usuario = $this->exigirSesion();
        if ($this->esPost()) {
            $this->citas->cancelarDeUsuario((int) ($_POST['id'] ?? 0), $usuario['id']);
        }
        $this->redirigir('mis-citas', 'Cita cancelada.');
    }

    public function agenda(): void
    {
        $this->exigirAdmin();
        $citas = $this->citas->agenda(date('Y-m-d'));
        require __DIR__ . '/../views/citas/agenda.php';
    }

    public function cambiarEstado(): void
    {
        $this->exigirAdmin();
        $estado = $_POST['estado'] ?? '';
        if ($this->esPost() && in_array($estado, ['confirmada', 'cancelada'], true)) {
            $this->citas->cambiarEstado((int) ($_POST['id'] ?? 0), $estado);
            $this->redirigir('agenda', 'Cita ' . $estado . '.');
        }
        $this->redirigir('agenda');
    }
}