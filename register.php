<?php
    require_once __DIR__ . "/config/functions.php";
    require_once __DIR__ . "/config/validations.php";
    require_once __DIR__ . "/config/db.php";
    require_once __DIR__ . "/config/sendMailNotify.php";
    require_once __DIR__ . "/config/email/templates/message.php";


    if (isLoggedIn()) {
        redirect('/dashboard/');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $error = validateInputsRegister($nombre, $email, $password);

        if ($error) {
            notify($error, 'error');
        } else {
            $userExistsQuery = "SELECT id FROM users WHERE email = ?";
            $consulta = $pdo->prepare($userExistsQuery);
            $consulta->execute([$email]);

            if ($consulta->rowCount() > 0) {
                notify("El correo electrónico ya está registrado.", 'error');
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $role = 'user';

                $sql = "INSERT INTO users (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
                $crearUsuario = $pdo->prepare($sql);
                $crearUsuario->execute([$nombre, $email, $hashedPassword, $role]);
                

                //Notificacion Admin
                sendMailNotify(
                    ['nombre' => NAME_SISTEMA, 'email' => MAIL_USERNAME],
                    "Gestión de Usuarios",
                    notifyRegister(
                        ['title' => '¡Se registro un nuevo profesor en el Sistema!', 'subtitle' => 'Los datos del profesor son:'],
                        $nombre, 
                        $email)
                );
                //Notificacion al Profesor 
                sendMailNotify(
                    ['nombre' => $nombre, 'email' => $email], 
                    ASUNTO_SISTEMA, 
                    notifyRegister(
                        ['title' => '¡Registro exitoso!', 'subtitle' => 'Tus datos son:'],
                        $nombre, 
                        $email
                    )
                );


                notify("Registro exitoso. Ahora puedes iniciar sesión.", 'success');
                redirect('/login.php');
            }
        }
    }

    $titulo = "Registrarse | SIRAUCA - Sistema de Reserva de Aulas y Carros del IFTS N° 4";

    $slides = [
        [
            "imagen" => "assets/images/imagen_instituto.png",
            "texto" => "Reservá tu aula fácilmente"
        ],
        [
            "imagen" => "assets/images/ifts_fondo2.jpg",
            "texto" => "Desarrollado por sus estudiantes"
        ],
    ];
?>

<?php
    include __DIR__ . "/components/header.php";
?>

<main class="auth-page">
    <?php
        include "./components/slider.php";
    ?>
    <form class="auth-form" method="post">
        <h3 class="auth-title">Registrarse</h3>
        <div class="auth-field">
            <label class="auth-label" for="nombre">Nombre:</label>
            <input class="auth-input" type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
        </div>
        <div class="auth-field">
            <label class="auth-label" for="email">Correo electrónico:</label>
            <input class="auth-input" type="email" id="email" name="email" placeholder="mail@dominio.com" required>
        </div>
        <div class="auth-field">
            <label class="auth-label" for="password">Contraseña:</label>
            <input class="auth-input" type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            <img id="eye" src="<?= BASE_URL . '/' . htmlspecialchars('assets/images/eye-open.svg') ?>" alt="Mostrar/Ocultar contraseña" class="eye-icon">
        </div>

        <div class="auth-actions"> 
            <button class="auth-link" type="submit">Registrarme</button>
            <div class="auth-divider">
                <span class="auth-lineDivisor"></span>
                <span class="auth-circle"></span>
                <span class="auth-lineDivisor"></span>
            </div>
            <span class="auth-questionRegister">¿Ya tienes una cuenta?</span>
            <a class="btn auth-button" href="<?= BASE_URL ?>/login.php">Iniciar sesión</a>
        </div>
    </form>
    <script src="<?= BASE_URL ?>/assets/js/slider.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/eyePass.js"></script>

</main>

<?php
    include "./components/footer.php";
?>
