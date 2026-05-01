// URL où se trouve le répertoire "server" sur mmi.unilim.fr
let HOST_URL = "https://mmi.unilim.fr/~le-flohic4/SAE2.03-YannLEFLOHIC"; //"http://mmi.unilim.fr/~????"; // CHANGE THIS TO MATCH YOUR CONFIG

let DataMovie = {};

/**
 * Ajoute un nouveau film via une requête POST au serveur
 * @param {FormData} formData - Les données du formulaire à envoyer
 */
DataMovie.add = async function (formData) {
  // Envoi des données en POST au serveur
  let answer = await fetch(HOST_URL + "/server/script.php?todo=addmovie", {
    method: "POST",
    body: formData,
  });

  // Conversion de la réponse en JSON
  let data = await answer.json();

  // Retour de la réponse du serveur
  return data;
};

DataMovie.requestCategories = async function () {
  let answer = await fetch(HOST_URL + "/server/script.php?todo=readcategories");
  let data = await answer.json();

  return data;
};

// Copié depuis app/data/dataMovie.js — recherche films
DataMovie.search = async function (query) {
  let answer = await fetch(
    HOST_URL + "/server/script.php?todo=searchmovies&query=" + query,
  );
  let data = await answer.json();
  return data; // pas besoin d'enrichir les images ici, c'est l'admin
};

// Nouvelle fonction pour modifier le statut featured
DataMovie.updateFeatured = async function (id, featured) {
  let answer = await fetch(
    HOST_URL +
      "/server/script.php?todo=updatefeaturedstatus&id=" +
      id +
      "&featured=" +
      featured,
  );
  let data = await answer.json();
  return data;
};

export { DataMovie };
