<?php
    $alumnos = [
        ["nombre" => "Damian", "apellido" => "Luna"],
        ["nombre" => "Daniel", "apellido" => "Lopez"],
        ["nombre" => "Jonatan", "apellido" => "Palavecino"],
    ];

    foreach ($alumnos as $alumno) {
        echo $alumno["nombre"] . " " . $alumno["apellido"] . "<br>";
    };
?>