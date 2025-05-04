console.log('main.js cargado correctamente');

// Mostrar totales generales (controladas / total)
async function cargarTotalesGenerales() {
    try {
        const res = await fetch('index.php?accion=totalesPorCanal');
        const data = await res.json();
        // console.log(data)
        document.getElementById('contador-totales').textContent = data.total;
        document.getElementById('contador-controlados').textContent = data.controlados;
    } catch (error) {
        console.error("Error al obtener los totales generales:", error);
    }
}

// Mostrar totales NO controlados por canal
async function obtenerTotalesPorCanal() {
    try {
        const res = await fetch('index.php?accion=totalesPorCanal');
        const data = await res.json();
        // console.log(data)
        if (data.success) {
            document.getElementById('canal-verde').textContent = data.verde;
            document.getElementById('canal-amarillo').textContent = data.amarillo;
            document.getElementById('canal-rojo').textContent = data.rojo;
        } else {
            console.error('Error al obtener totales por canal:', data.message);
        }
    } catch (error) {
        console.error('Error en la solicitud de totales por canal:', error);
    }
}


// Mostrar toast con SweetAlert
function mostrarToast(tipo, titulo, texto,color) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        target: "body",
        background:color,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
            toast.style.fontSize = '1.5rem';
            toast.style.padding = '1.5rem';
            toast.style.width = '450px';
        }
    });
    Toast.fire({
        icon: tipo,
        title: titulo,
        text: texto,
    });
}

// Evento al enviar formulario
document.querySelector("#form-controlar").addEventListener("submit", async (e) => {
    e.preventDefault();

    const nroGuia = document.querySelector("#nro_guia").value.trim();
    if (!nroGuia) {
        console.log("Por favor ingresá un número de guía.");
        return;
    }

    try {
        const response = await fetch("index.php?accion=controlar", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ nro_guia: nroGuia })
        });
        var inputGuia = document.getElementById('nro_guia')
        const data = await response.json();
         console.log(data.canal);
        var color =""
        if(data.canal == "verde"){
            color = "#98d883";
        }if(data.canal == "rojo"){
            color = "#d88392";
        }if(data.canal == "amarillo"){
            color = "#eadd5c";
        }
        // else{
        //     color = "#777670";
        // }
        switch (data.estado) {
            case "ya_controlado":
                mostrarToast("warning", `Guía: ${data.nro_guia}`, `Ya fue controlada. Canal: ${data.canal}`,color);
                console.log(data)
                inputGuia.value='';
                inputGuia.focus();
                break;
            case "ok":
                mostrarToast("success", `Guía: ${data.nro_guia}`, `Controlado canal: ${data.canal}`,color);
                await cargarTotalesGenerales();
                await obtenerTotalesPorCanal();
                inputGuia.value='';
                inputGuia.focus();
                break;
            case "error":
                mostrarToast("error", "Guía no encontrada", data.mensaje || "",color);
                inputGuia.value='';            
                inputGuia.focus();
                break;
            default:
                console.warn("Respuesta desconocida:", data);
                inputGuia.value='';
                inputGuia.focus();
        }

    } catch (error) {
        console.error("Error al controlar el paquete:", error);
        mostrarToast("error", "Error", "Ocurrió un error al enviar los datos.",color);
    }
});

// Cargar datos al iniciar
window.addEventListener("DOMContentLoaded", () => {
    cargarTotalesGenerales();
    obtenerTotalesPorCanal();
});


  