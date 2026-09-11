<?php
    require_once __DIR__ . "/config/functions.php";
    require_once __DIR__ . "/config/db.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if ($email == "" || $password == "") {
            echo "Por favor, ingrese su correo electrónico y contraseña.";
        } else {
            echo "Correo electrónico: " . $email . "<br>";
            echo "Contraseña: " . $password;
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
        </div>
        
        <button class="auth-button" type="submit">Iniciar sesión</button>
    </form>
    <script src="<?= BASE_URL ?>/assets/js/slider.js"></script>
</main>



<?php
    include "./components/footer.php";
?>