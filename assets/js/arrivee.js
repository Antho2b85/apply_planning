import { Modal } from "bootstrap";
// Modal page arrivée
let boutonActifArrivee = null;

const openModalArrivee = document.getElementById("modalArrivee");
openModalArrivee.addEventListener("show.bs.modal", (event) => {
    const ButtonModalArrivee = event.relatedTarget;
    const id = ButtonModalArrivee.dataset.id;
    const navireId = ButtonModalArrivee.dataset.navireId;
    const heureArrivee = ButtonModalArrivee.dataset.heureArrivee;
    const heureDepart = ButtonModalArrivee.dataset.heureDepart;
    const quai = ButtonModalArrivee.dataset.quai;
    boutonActifArrivee = event.relatedTarget;

    document.getElementById("arrivee-id").value = id;

    document.getElementById("navire-select").value = navireId;

    document.getElementById("heureArrivee").value = heureArrivee;

    document.getElementById("heureDepart").value = heureDepart;

    document.getElementById("quai-select").value = quai;
});

// Envoi des données de l'arrivée
document
    .getElementById("btnAjouterArrivee")
    .addEventListener("click", async (event) => {
        const formDataArrivee = new FormData(
            document.getElementById("formArrivee"),
        );
        try {
            const reponse = await fetch("/admin/arrivee/form", {
                method: "POST",
                body: formDataArrivee,
            });
            const data = await reponse.json();

            if (data.status === "ok") {
                location.reload();
            } else {
                alert(data.message ?? "Une erreur est survenue.");
            }
        } catch (error) {
            console.error("Erreur lors de la requête :", error);
            alert("Une erreur est survenue, veuillez réessayer.");
        }
    });
