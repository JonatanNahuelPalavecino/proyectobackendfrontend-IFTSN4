const botonesEntregar = document.querySelectorAll(".entregar-carro");

botonesEntregar.forEach((boton) => {
    boton.addEventListener("click", async () => {
        const reservaId = boton.dataset.id;

        const ventanaPdf = window.open("", "_blank");

        try {
            const respuesta = await fetch(
                boton.dataset.entregarUrl,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "id=" + encodeURIComponent(reservaId)
                }
            );

            const resultado = await respuesta.json();

            if (!resultado.ok) {
                ventanaPdf.close();
                alert(resultado.mensaje);
                return;
            }

            ventanaPdf.location.href =
                boton.dataset.remitoUrl + "?id=" + reservaId;

            window.location.href =
                boton.dataset.volverUrl
                + "?success="
                + encodeURIComponent("Se entregó el carro reservado");

        } catch (error) {
            ventanaPdf.close();

            alert("Ocurrió un error al entregar el carro.");
        }
    });
});