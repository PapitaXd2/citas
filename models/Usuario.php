<?php

class Usuario
{
    public function __construct(private PDO $db) {}

    public function buscarPorCorreo(string $correo): ?array
    {
        $consulta = $this->db->prepare('SELECT * FROM usuarios WHERE correo = ?');
        $consulta->execute([$correo]);
        return $consulta->fetch() ?: null;
    }

    public function crear(string $nombre, string $correo, string $telefono, string $contrasena): void
    {
        $sql = 'INSERT INTO usuarios (nombre, correo, telefono, contrasena) VALUES (?, ?, ?, ?)';
        $this->db->prepare($sql)->execute([
            $nombre,
            $correo,
            $telefono,
            password_hash($contrasena, PASSWORD_DEFAULT),   // nunca se guarda la contraseña tal cual
        ]);
    }
}
