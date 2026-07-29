import { Modal } from "bootstrap";
// ==========================================
// Modal planning - modification des créneaux
let boutonActif = null;
let boutonActifAbsence = null;
// Pré remplissafe du modal avec les données de la cellule cliquée
const openModal = document.getElementById("exampleModal");
openModal.addEventListener("show.bs.modal", (event) => {
    const buttonModal = event.relatedTarget;
    const heureDebutMatin = buttonModal.dataset.heureDebutMatin;
    const heureFinMatin = buttonModal.dataset.heureFinMatin;
    const heureDebutAprem = buttonModal.dataset.heureDebutAprem;
    const heureFinAprem = buttonModal.dataset.heureFinAprem;
    const posteMatin = buttonModal.dataset.posteMatin;
    const posteAprem = buttonModal.dataset.posteAprem;
    boutonActif = event.relatedTarget;

    const recupHorairesMatinDebut =
        document.getElementById("horairesMatinDebut");
    recupHorairesMatinDebut.value = heureDebutMatin;

    const recupHorairesMatinFin = document.getElementById("horairesMatinFin");
    recupHorairesMatinFin.value = heureFinMatin;

    const recupHorairesApremDebut =
        document.getElementById("horairesApremDebut");
    recupHorairesApremDebut.value = heureDebutAprem;

    const recupHorairesApremFin = document.getElementById("horairesApremFin");
    recupHorairesApremFin.value = heureFinAprem;

    const recupPosteMatin = document.getElementById("post-select-matin");
    recupPosteMatin.value = posteMatin;

    const recupPosteAprem = document.getElementById("post-select-aprem");
    recupPosteAprem.value = posteAprem;

    const recupCreneauMatinId = document.getElementById("creneauMatinId");
    recupCreneauMatinId.value = buttonModal.dataset.creneauMatinId;

    const recupCreneauApremId = document.getElementById("creneauApremId");
    recupCreneauApremId.value = buttonModal.dataset.creneauApremId;

    document.getElementById("userId").value = buttonModal.dataset.userId;

    const recupDate = document.getElementById("date");
    recupDate.value = buttonModal.dataset.date;
});

// Envoi des données de planification au serveur via fetch
document
    .getElementById("btnEnvoyer")
    .addEventListener("click", async (event) => {
        const formData = new FormData(
            document.getElementById("formPlanification"),
        );
        try {
            const response = await fetch("/admin/planning/form", {
                method: "POST",
                body: formData,
            });
            const result = await response.json();
            if (result.status === "ok") {
                const modal = Modal.getInstance(openModal);
                modal.hide();

                const celluleMatin = boutonActif
                    .closest("td")
                    .querySelector(".cellule-matin");
                const celluleAprem = boutonActif
                    .closest("td")
                    .querySelector(".cellule-aprem");
                celluleMatin.innerHTML = `${formData.get("horairesMatinDebut")} - ${formData.get("horairesMatinFin")} : ${formData.get("post-matin")}`;
                celluleAprem.innerHTML = `${formData.get("horairesApremDebut")} - ${formData.get("horairesApremFin")} : ${formData.get("post-aprem")}`;
            }
        } catch (error) {
            console.error("Erreur lors de la requête :", error);
            alert("Une erreur est survenue, veuillez réessayer.");
        }
    });
// ==========================================

// Modal absence - saisie d'une absence

// Pré remplissage du modal absence avec l'agent et la date concernés
const modalAbscence = document.getElementById("modalAbsence");
modalAbscence.addEventListener("show.bs.modal", (event) => {
    const buttonModalAbs = event.relatedTarget;
    const userId = buttonModalAbs.dataset.userId;
    const date = buttonModalAbs.dataset.date;
    boutonActifAbsence = event.relatedTarget;

    document.getElementById("absenceUserId").value =
        buttonModalAbs.dataset.userId;
    document.getElementById("absenceDate").value = buttonModalAbs.dataset.date;
    document.getElementById("debutAbs").value = buttonModalAbs.dataset.date;
});

// Envoi du motif d'absence au serveur via fetch
document
    .getElementById("btnEnvoyerAbsence")
    .addEventListener("click", async (event) => {
        const formData = new FormData(document.getElementById("formAbsence"));
        try {
            const reponse = await fetch("/admin/absence/form", {
                method: "POST",
                body: formData,
            });
            const result = await reponse.json();
            if (result.status === "ok") {
                const modal = Modal.getInstance(modalAbscence);
                modal.hide();

                const celluleMatin = boutonActifAbsence
                    .closest("td")
                    .querySelector(".cellule-matin");
                const celluleAprem = boutonActifAbsence
                    .closest("td")
                    .querySelector(".cellule-aprem");
                celluleMatin.innerHTML = `${formData.get("motif")}`;
                celluleAprem.innerHTML = `${formData.get("motif")}`;
            }
        } catch (error) {
            console.error("Erreur lors de la requête :", error);
            alert("Une erreur est survenue, veuillez réessayer.");
        }
    });

// Déclenchement de l'impression pour export PDF
document.getElementById("btnExporterPDF").addEventListener("click", () => {
    window.print();
});
