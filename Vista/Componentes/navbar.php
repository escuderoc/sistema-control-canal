<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">📦 Gestión de Paquetes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav" aria-controls="menuNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menuNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link<?= ($_GET['vista'] ?? 'control') === 'controlVista' ? ' active' : '' ?>" href="index.php?vista=controlVista">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?= ($_GET['vista'] ?? '') === 'importar' ? ' active' : '' ?>" href="index.php?vista=importar">Importar Guías</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php?vista=usuarios">Usuarios</a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
        <li>  
          <a href="index.php?accion=logout" class="btn btn-danger">Cerrar sesión</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>