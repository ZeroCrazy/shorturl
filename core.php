<?php
    error_reporting(0);
    // Si no sabes importar una base de datos te lo explico muy rápido.
    // Abres phpMyAdmin, seleccionas tu base de datos, le das en importar y subes el archivo database.sql
    // Sigue los pasos de arriba si no sabes importarlo.

    // Conexión al host y a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'demo');

    // Ajustes de la página web
    $sitename = "CutUrl"; // Nombre de la web.
    $site = "http://localhost"; // Enlace de tu web (por defecto al ser en tu local es http://localhost).
?>