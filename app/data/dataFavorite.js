let HOST_URL = "https://mmi.unilim.fr/~le-flohic4/SAE2.03-YannLEFLOHIC";

let DataFavorite = {};

DataFavorite.add = async function (id_profile, id_movie) {
  let answer = await fetch(
    HOST_URL +
      "/server/script.php?todo=addfavorite&id_profile=" +
      id_profile +
      "&id_movie=" +
      id_movie,
  );
  let data = await answer.json();
  return data;
};

DataFavorite.read = async function (id_profile) {
  let answer = await fetch(
    HOST_URL + "/server/script.php?todo=readfavorites&id_profile=" + id_profile,
  );
  let data = await answer.json();
  return data;
};

DataFavorite.remove = async function (id_profile, id_movie) {
  // Construction de l'URL avec les paramètres de requête
  // to do=removefavorite : action serveur pour supprimer un favori
  let answer = await fetch(
    HOST_URL +
      "/server/script.php?todo=removefavorite&id_profile=" +
      id_profile +
      "&id_movie=" +
      id_movie,
  );

  // Conversion de la réponse du serveur en objet JSON
  let data = await answer.json();

  // Retour des données (généralement une confirmation de suppression)
  return data;
};

export { DataFavorite, HOST_URL };
