// =====================
// ARCHIVO: Assets/usuarios.js
// =====================
console.log("usuarios.js cargado");

document.addEventListener("DOMContentLoaded", () => {
    cargarUsuarios();

    // Agregar usuario
    document.getElementById("formUsuario").addEventListener("submit", async (e) => {
        e.preventDefault();

        const datos = {
            nombre: document.getElementById("nombre").value,
            usuario: document.getElementById("usuario").value,
            password: document.getElementById("password").value,
            rol: document.getElementById("rol").value
        };

        try {
            const res = await fetch("index.php?accion=crearUsuario", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(datos)
            });

            const data = await res.json();
            if (data.success) {
                Swal.fire("✅ Éxito", data.mensaje, "success");
                e.target.reset();
                cargarUsuarios();
            } else {
                Swal.fire("❌ Error", data.mensaje, "error");
            }
        } catch (err) {
            console.error(err);
            Swal.fire("❌ Error", "No se pudo agregar el usuario", "error");
        }
    });
});

// Cargar usuarios
async function cargarUsuarios() {
    try {
        const res = await fetch("index.php?accion=listarUsuarios");
        // const data = await res.json();
        const texto = await res.text();
        console.log("Respuesta cruda:", texto);
        const data = JSON.parse(texto);
        const tbody = document.getElementById("tbody-usuarios");
        tbody.innerHTML = "";

        if (data.success) {
            data.usuarios.forEach(u => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${u.id}</td>
                    <td>${u.nombre}</td>
                    <td>${u.usuario}</td>
                    <td>${u.rol}</td>
                    <td>
                        <button class="btn btn-warning btn-sm me-1" onclick="mostrarModalEditar(${u.id}, '${u.nombre}', '${u.usuario}', '${u.rol}')">✏️ Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(${u.id})">🗑️ Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center">No hay usuarios</td></tr>`;
        }
    } catch (err) {
        console.error("Error al cargar usuarios:", err);
    }
}

// Mostrar modal de edición
function mostrarModalEditar(id, nombre, usuario, rol) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_nombre").value = nombre;
    document.getElementById("edit_usuario").value = usuario;
    document.getElementById("edit_rol").value = rol;

    const modal = new bootstrap.Modal(document.getElementById("modalEditarUsuario"));
    modal.show();
}

// Guardar edición
document.getElementById("formEditarUsuario").addEventListener("submit", async function (e) {
    e.preventDefault();

    const datos = {
        id: document.getElementById("edit_id").value,
        nombre: document.getElementById("edit_nombre").value,
        usuario: document.getElementById("edit_usuario").value,
        rol: document.getElementById("edit_rol").value
    };

    try {
        const res = await fetch("index.php?accion=editarUsuario", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        });

        const data = await res.json();
        if (data.success) {
            Swal.fire("✅ Actualizado", data.mensaje, "success");
            bootstrap.Modal.getInstance(document.getElementById("modalEditarUsuario")).hide();
            cargarUsuarios();
        } else {
            Swal.fire("❌ Error", data.mensaje, "error");
        }
    } catch (err) {
        console.error(err);
        Swal.fire("❌ Error", "No se pudo editar el usuario", "error");
    }
});

// Eliminar usuario
async function eliminarUsuario(id) {
    const confirmacion = await Swal.fire({
        title: "¿Eliminar usuario?",
        text: `¿Estás seguro de eliminar el usuario con ID ${id}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    });

    if (confirmacion.isConfirmed) {
        try {
            const res = await fetch(`index.php?accion=eliminarUsuario&id=${id}`);
            const data = await res.json();
            if (data.success) {
                Swal.fire("✅ Eliminado", data.mensaje, "success");
                cargarUsuarios();
            } else {
                Swal.fire("❌ Error", data.mensaje, "error");
            }
        } catch (err) {
            console.error(err);
            Swal.fire("❌ Error", "No se pudo eliminar el usuario", "error");
        }
    }
}
