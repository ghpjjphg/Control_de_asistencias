<?php 
session_start();
$conexion = mysqli_connect(
    'localhost',//nombre del servidor
    'root',//usuario
    '',//contraseña
    'progresacademic'// nombre de la base de datos
);