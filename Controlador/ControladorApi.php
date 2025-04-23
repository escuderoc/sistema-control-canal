<!-- // controlador_api.php -->
<?php
require_once(__DIR__ . "/Controlador/PaquteControlador.php");

$controlador = new PaqueteControlador();
$controlador->controlar();
