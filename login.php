<?php
    require_once __DIR__ . "/config/functions.php";
    require_once __DIR__ . "/config/db.php";

    if (isLoggedIn()) {
        redirect('/dashboard/');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $error = validateInputsLogin($email, $password);

        if ($error) {
            notify($error, "error");
        } else {
            $sql = "SELECT * FROM users WHERE email = ?";
            $consulta = $pdo->prepare($sql);
            $consulta->execute([$email]);
            $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password'])) {
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'email' => $usuario['email'],
                    'rol' => $usuario['rol']
                ];

                notify("Inicio de sesión exitoso.", "success");
                redirect('/dashboard/index.php');
            } else {
                notify("Correo electrónico o contraseña incorrectos.", "error");
            }
        }
    }

    $titulo = "Iniciar sesión | Reservá tu aula";

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
        <h3 class="auth-title">Iniciar sesión</h3>
        <div class="auth-field">
            <label class="auth-label" for="email">Correo electrónico:</label>
            <input class="auth-input" type="email" id="email" name="email" placeholder="mail@dominio.com" required>
        </div>
        <div class="auth-field">
            <label class="auth-label" for="password">Contraseña:</label>
            <input class="auth-input" type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            <img id="eye" src="<?= BASE_URL . '/' . htmlspecialchars('assets/images/eye-open.svg') ?>" alt="Mostrar/Ocultar contraseña" class="eye-icon">
        </div>
        <a class="auth-link" href="<?= BASE_URL ?>/register.php">¿No tienes una cuenta? Regístrate</a>
        
        <button class="auth-button" type="submit">Iniciar sesión</button>
    </form>
    <script src="<?= BASE_URL ?>/assets/js/slider.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/eyePass.js"></script>
</main>



<?php
    include "./components/footer.php";
?>