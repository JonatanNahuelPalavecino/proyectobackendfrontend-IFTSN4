<?php

    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    };

    function redirect($location) {
        header("Location: " . BASE_URL . $location);
        exit();
    }

    function getUser() {
        return $_SESSION['usuario'] ?? null;
    }

?>