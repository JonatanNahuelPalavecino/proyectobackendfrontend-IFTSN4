<?php

require_once __DIR__ '/config/db.php';

$nombre = 'Bedelia_principal';
$email = 'bedelia@ifts4.edu.ar';
$password_plana= 'bedelia0';
$rol = 'admin';

// 1. Encriptación obligatoria directamente en el código
$password_hash = password_hash ($password_plana, PASSWORD_DEFAULT)

// 2. Inyección segura a la base de datos
$sql = "INSERT INTO users (nombre, email, password, rol) VALUES (?,?,?,?)";
$stmt = $pdo->prepare($sql);

if ($stmt->execute ([$nombre,$email,$password_plana,$rol])) {
    echo "Administrador raiz ha sido creado correctamente. Elimine este archivo";
}



?>