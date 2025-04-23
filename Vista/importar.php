<?php
// =========================
// ARCHIVO: VIsta/importar.php
// =========================

// require_once(__DIR__ . "/../Controlador/PaqueteControlador.php");

// $controlador = new PaqueteControlador();
// $totales = $controlador->obtenerTotalesGeneralesSoloRetorno(); // función que retorna sin hacer echo
?>
<!DOCTYPE html>
<?php include_once 'Componentes/navbar.php'; ?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Importar Guías</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4">📦 Importar archivo Excel</h2>

        <form id="form-importar" class="mb-5" enctype="multipart/form-data" method="post">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <input type="file" class="form-control" name="archivoExcel" accept=".xlsx, .xls" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">📤 Importar</button>
                </div>
            </div>
        </form>

        <h4 class="mb-3">🔍 Filtrar guías</h4>
        <form id="form-filtros" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="filtro_fecha" class="form-label">Fecha</label>
                <input type="date" id="filtro_fecha" class="form-control" name="fecha">
            </div>
            <div class="col-md-3">
                <label for="filtro_canal" class="form-label">Canal</label>
                <select id="filtro_canal" class="form-select" name="canal">
                    <option value="">Todos</option>
                    <option value="verde">Verde</option>
                    <option value="amarillo">Amarillo</option>
                    <option value="rojo">Rojo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtro_controlado" class="form-label">¿Controlado?</label>
                <select id="filtro_controlado" class="form-select" name="controlado">
                    <option value="">Todos</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-success">🔍 Aplicar filtros</button>
            </div>
        </form>

        <h4 class="mb-3">📋 Guías importadas</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tabla-guias">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Guía</th>
                        <th>Fecha</th>
                        <th>Canal</th>
                        <th>¿Controlado?</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-guias">
                    <!-- Se cargan con JS -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal Editar Guía -->
<div class="modal fade" id="modalEditarGuia" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEditarGuia">
        <div class="modal-header">
          <h5 class="modal-title">✏️ Editar Guía</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_nro_guia">

          <div class="mb-3">
            <label for="edit_fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="edit_fecha" required>
          </div>

          <div class="mb-3">
            <label for="edit_canal" class="form-label">Canal</label>
            <input type="text" class="form-control" id="edit_canal" required>
          </div>

          <div class="mb-3">
            <label for="edit_controlado" class="form-label">¿Controlado?</label>
            <select class="form-select" id="edit_controlado">
              <option value="1">Sí</option>
              <option value="0">No</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <script src="/SISTEMA_CONTROL_CANAL/assets/importar.js"></script>
</body>
</html>
