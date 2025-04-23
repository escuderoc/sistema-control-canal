
<?php

?>
<!DOCTYPE html>
<?php include_once 'Componentes/navbar.php'; ?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Paquetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<div class="container mt-4">
    <h2>Gestión de Usuarios</h2>

    <form id="formUsuario" class="mb-4">
        <input type="hidden" name="id" id="usuario_id">
        <div class="row">
            <div class="col-md-3">
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-3">
                <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuario" required>
            </div>
            <div class="col-md-3">
                <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <div class="col-md-2">
                <select id="rol" name="rol" class="form-control" required>
                    <option value="">Rol</option>
                    <option value="admin">Admin</option>
                    <option value="usuario">Usuario</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success w-100">Guardar</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-usuarios">
            <!-- Los usuarios se cargarán aquí dinámicamente -->
        </tbody>
    </table>
</div>
<!-- Modal para editar usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditarUsuario" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarUsuarioLabel">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit_id">
        <div class="mb-3">
          <label for="edit_nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="edit_nombre" required>
        </div>
        <div class="mb-3">
          <label for="edit_usuario" class="form-label">Usuario</label>
          <input type="text" class="form-control" id="edit_usuario" required>
        </div>
        <div class="mb-3">
          <label for="edit_rol" class="form-label">Rol</label>
          <select class="form-control" id="edit_rol" required>
            <option value="">Seleccione rol</option>
            <option value="admin">Admin</option>
            <option value="usuario">Usuario</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<script src="Assets/usuarios.js"></script>
</body>
</html>