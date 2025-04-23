// =========================
// ARCHIVO: assets/importar.js
// =========================
console.log('importador desde excel corregido V11.2')
document.addEventListener("DOMContentLoaded", () => {
    // cargarGuias();

    document.getElementById("form-importar").addEventListener("submit", async (e) => {
        e.preventDefault();
        const form = e.target;
        const archivoInput = form.querySelector('input[name="archivoExcel"]');

        const formData = new FormData(form);
        formData.append("archivoExcel", archivoInput.files[0]);
        
        try {
            const res = await fetch("index.php?accion=importarExcel", {
                method: "POST",
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                Swal.fire("✅ Importación exitosa", data.mensaje, "success");
                form.reset();
                // cargarGuias(); // recargar tabla
            } else {
                Swal.fire("❌ Error", data.mensaje, "error");
            }
        } catch (err) {
            console.error(err);
            Swal.fire("❌ Error inesperado", "No se pudo importar el archivo ".err, "error");
        }
    });

    document.getElementById("form-filtros").addEventListener("submit", async (e) => {
        e.preventDefault();
        cargarGuias();
    });
});
async function cargarGuias() {
    const fecha = document.getElementById("filtro_fecha").value;
    const canal = document.getElementById("filtro_canal").value;
    const controlado = document.getElementById("filtro_controlado").value;

    const params = new URLSearchParams({
        accion: "filtrar",
        fecha,
        canal,
        controlado
    });

    try {
        const res = await fetch(`index.php?${params.toString()}`);
        const texto = await res.text(); // leemos el texto bruto una sola vez
        console.log("Respuesta cruda:", texto); // para depurar

        const data = JSON.parse(texto); // convertimos manualmente a JSON

        const tbody = document.getElementById("tbody-guias");
        tbody.innerHTML = "";

        if (data.success && data.guias.length > 0) {
            data.guias.forEach(g => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${g.id}</td>
                    <td>${g.nro_guia}</td>
                    <td>${g.fecha}</td>
                    <td>${g.canal}</td>
                    <td>${g.controlado == 1 ? "Sí" : "No"}</td>
                    <td>
                        <button class="btn btn-sm btn-warning me-1" onclick="editarGuia(${g.nro_guia})">✏️ Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarGuia(${g.nro_guia})">🗑️ Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            const tr = document.createElement("tr");
            tr.innerHTML = `<td colspan="6" class="text-center">No se encontraron guías</td>`;
            tbody.appendChild(tr);
        }
    } catch (err) {
        console.error("Error al cargar guías:", err);
    }
}

function editarGuia(nro_guia) {
    // Buscar los datos de la fila
    const fila = [...document.querySelectorAll("#tbody-guias tr")].find(tr =>
        tr.children[1].textContent == nro_guia
    );

    if (!fila) return;

    // Rellenar el modal
    document.getElementById("edit_nro_guia").value = nro_guia;
    document.getElementById("edit_fecha").value = fila.children[2].textContent;
    document.getElementById("edit_canal").value = fila.children[3].textContent;
    document.getElementById("edit_controlado").value = fila.children[4].textContent === "Sí" ? "1" : "0";

    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById("modalEditarGuia"));
    modal.show();
}

// Manejar el submit
document.getElementById("formEditarGuia").addEventListener("submit", async function (e) {
    e.preventDefault();

    const datos = {
        accion: "editarGuia",
        nro_guia: document.getElementById("edit_nro_guia").value,
        fecha: document.getElementById("edit_fecha").value,
        canal: document.getElementById("edit_canal").value,
        controlado: document.getElementById("edit_controlado").value
    };
    console.log(datos)

    try {
        const res = await fetch("index.php?accion=editarGuia", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        });

        const result = await res.json();

        if (result.success) {
            Swal.fire("✅ Actualizado", result.mensaje, "success");
            const modal = bootstrap.Modal.getInstance(document.getElementById("modalEditarGuia"));
            modal.hide();
            cargarGuias();
        } else {
            Swal.fire("❌ Error", result.mensaje, "error");
        }
    } catch (err) {
        console.error(err);
        Swal.fire("❌ Error", "No se pudo actualizar la guía", "error");
    }
});


async function eliminarGuia(id) {
    const confirmacion = await Swal.fire({
        title: "¿Eliminar guía?",
        text: `¿Estás seguro de eliminar la guía con ID ${id}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    });

    if (confirmacion.isConfirmed) {
        try {
            const res = await fetch(`index.php?accion=eliminarGuia&nro_guia=${id}`, {
                method: "GET"
            });

            const data = await res.json();
            if (data.success) {
                Swal.fire("✅ Eliminado", data.message, "success");
                cargarGuias();
            } else {
                Swal.fire("❌ Error", data.message, "error");
            }
        } catch (err) {
            console.error(err);
            Swal.fire("❌ Error", "No se pudo eliminar la guía", "error");
        }
    }
}
// document.getElementById("formEditarGuia").addEventListener("submit", async function (e) {
//     e.preventDefault();

//     const datos = {
//         nro_guia: document.getElementById("edit_nro_guia").value,
//         fecha: document.getElementById("edit_fecha").value,
//         canal: document.getElementById("edit_canal").value,
//         controlado: document.getElementById("edit_controlado").value
//     };
//     console.log(datos)
//     try {
//         const res = await fetch(`../Controlador/PaqueteControlador.php?accion=editarGuia`, {
//             method: "POST",
//             headers: { "Content-Type": "application/json" },
//             body: JSON.stringify(datos) // sin incluir "accion" aquí
//         });

//         const data = await res.json();
//         if (data.success) {
//             Swal.fire("✅ Éxito", data.mensaje, "success");
//             bootstrap.Modal.getInstance(document.getElementById("modalEditarGuia")).hide();
//             cargarGuias(); // Recarga la tabla
//         } else {
//             Swal.fire("❌ Error", data.mensaje, "error");
//         }
//     } catch (err) {
//         console.error(err);
//         Swal.fire("❌ Error", "No se pudo editar la guía", "error");
//     }
// });

