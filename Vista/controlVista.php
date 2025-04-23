<!DOCTYPE html>
<?php include_once 'Componentes/navbar.php'; ?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Paquetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="p-4 bg-light">
    <div class="container">
        <h2 class="mb-4 text-center">Control de Paquetes</h2>
        
        <form id="form-controlar" class="mb-4">
            <div class="input-group">
                <input type="text" name="nro_guia" id="nro_guia" class="form-control" placeholder="Ingresar número de guía" required>
                <button type="submit" class="btn btn-primary" id="btnControlar">Controlar</button>
            </div>
        </form>
        <div class="row text-center">
            <div class="col">
            <div id="totales-general" class="mb-3">
                <strong>Totales:</strong> <span id="contador-totales"></span> / <span id="contador-controlados"></span>
            </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col">
                <div class="card border-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-success">Canal Verde</h5>
                        <p class="card-text fs-4" id="canal-verde"><strong></strong></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-warning">Canal Amarillo</h5>
                        <p class="card-text fs-4" id="canal-amarillo"><strong></strong></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Canal Rojo</h5>
                        <p class="card-text fs-4" id="canal-rojo"><strong></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets/main.js"></script>
</body>
</html>
