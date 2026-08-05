import { Modal } from "bootstrap";

// Modal page prévisions
let boutonActifPrevision = null;

const openModalPrevision = document.getElementById("modalPrevisions");
openModalPrevision.addEventListener("show.bs.modal", (event) => {
    const ButtonModalPrevision = event.relatedTarget;

    const id = ButtonModalPrevision.dataset.id;
    const agentsReserves = ButtonModalPrevision.dataset.agentsReserves;
    const totalRemorques = ButtonModalPrevision.dataset.totalRemorques;
    const embarque = ButtonModalPrevision.dataset.embarque;
    const titres = ButtonModalPrevision.dataset.titres;
    const attentes = ButtonModalPrevision.dataset.attentes;
    const agentsControle = ButtonModalPrevision.dataset.agentsControle;
    const totalPassagers = ButtonModalPrevision.dataset.totalPassagers;
    const autosBasses = ButtonModalPrevision.dataset.autosBasses;
    const hauteurs = ButtonModalPrevision.dataset.hauteurs;
    const attelages = ButtonModalPrevision.dataset.attelages;
    const motos = ButtonModalPrevision.dataset.motos;
    const controles = ButtonModalPrevision.dataset.controles;
    const aVenir = ButtonModalPrevision.dataset.aVenir;
    boutonActifPrevision = event.relatedTarget;

    document.getElementById("prevision-id").value = id;
    document.getElementById("agentsReserves").value = agentsReserves;
    document.getElementById("totalRemorques").value = totalRemorques;
    document.getElementById("embarque").value = embarque;
    document.getElementById("titres").value = titres;
    document.getElementById("attentes").value = attentes;
    document.getElementById("agentsControle").value = agentsControle;
    document.getElementById("totalPassagers").value = totalPassagers;
    document.getElementById("autosBasses").value = autosBasses;
    document.getElementById("hauteurs").value = hauteurs;
    document.getElementById("attelages").value = attelages;
    document.getElementById("motos").value = motos;
    document.getElementById("controles").value = controles;
    document.getElementById("aVenir").value = aVenir;
});

// Envoi les donnnées de prévisions
document
    .getElementById("btnEnvoyerPrevision")
    .addEventListener("click", async (event) => {
        const formDataPrevision = new FormData(
            document.getElementById("formPrevision"),
        );
        try {
            const reponse = await fetch("/admin/prevision/form", {
                method: "POST",
                body: formDataPrevision,
            });
            const data = await reponse.json();

            if (data.status === "ok") {
                location.reload();
            } else {
                alert(data.message ?? "Une erreur est survenue.");
            }
        } catch (error) {
            console.error("Erreur lors de la requête :", error);
            alert("Une erreur est survenue, veuillez réessayer");
        }
    });
