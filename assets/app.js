import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "./stimulus_bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");

// Envoi des données new agent via fetch - modal ajouter agent
const modalAjouterAgent = document.getElementById("modalAjouterAgent");
document
    .getElementById("btnAjouterAgent")
    .addEventListener("click", async (event) => {
        const formData = new FormData(
            document.getElementById("formAjouterAgent"),
        );
        try {
            const reponse = await fetch("/admin/agent/nouveau", {
                method: "POST",
                body: formData,
            });
            const result = await reponse.json();
            if (result.status === "ok") {
                const modal = Modal.getInstance(modalAjouterAgent);
                modal.hide();
            }
        } catch (error) {
            console.error("Erreur lors de la requête :", error);
            alert("Une erreur est survenue, veuillez réessayer");
        }
    });
