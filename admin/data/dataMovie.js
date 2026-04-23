// URL où se trouve le répertoire "server" sur mmi.unilim.fr
let HOST_URL = "https://mmi.unilim.fr/~le-flohic4/SAE2.03-YannLEFLOHIC"; //"http://mmi.unilim.fr/~????"; // CHANGE THIS TO MATCH YOUR CONFIG

let DataMovie = {};

/**
 * Ajoute un nouveau film via une requête POST au serveur
 * @param {FormData} formData - Les données du formulaire à envoyer
 * @returns {Promise<Object>} La réponse du serveur
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

export { DataMovie };
