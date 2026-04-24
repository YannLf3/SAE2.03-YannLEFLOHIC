// URL où se trouve le répertoire "server" sur mmi.unilim.fr
let HOST_URL = "https://mmi.unilim.fr/~le-flohic4/SAE2.03-YannLEFLOHIC"; //"http://mmi.unilim.fr/~????"; // CHANGE THIS TO MATCH YOUR CONFIG

let DataMovie = {};

DataMovie.requestMovies = async function () {
  // fetch permet d'envoyer une requête HTTP à l'URL spécifiée.
  // L'URL est construite en concaténant HOST_URL à "/server/script.php?direction=" et la valeur de la variable dir.
  // L'URL finale dépend de la valeur de HOST_URL et de dir.
  let answer = await fetch(HOST_URL + "/server/script.php?todo=readmovies");
  // answer est la réponse du serveur à la requête fetch.
  // On utilise ensuite la méthode json() pour extraire de cette réponse les données au format JSON.
  // Ces données (data) sont automatiquement converties en objet JavaScript.
  let data = await answer.json();

  for (let i = 0; i < data.length; i++) {
    data[i].titre = data[i].name;
    data[i].image = HOST_URL + "/server/images/" + data[i].image;
  }
  // Enfin, on retourne ces données.
  return data;
};

DataMovie.requestCategories = async function () {
  let answer = await fetch(HOST_URL + "/server/script.php?todo=readcategories");
  let data = await answer.json();

  return data;
};

DataMovie.requestMovieDetail = async function (id) {
  try {
    let answer = await fetch(
      HOST_URL + "/server/script.php?todo=readmoviedetail&id=" + id,
    );

    // Si le serveur répond autre chose que 200, on considère que c'est une erreur
    if (!answer.ok) {
      return null;
    }

    let data = await answer.json();

    if (!data || data === false) {
      return null;
    }

    if (data.image) {
      data.image = HOST_URL + "/server/images/" + data.image;
    }

    data.titre = data.name;
    data.category = data.category ?? data.id_category ?? "Non renseigné";

    return data;
  } catch (error) {
    console.error("Erreur lors de la récupération du film :", error);
    return null;
  }
};

export { DataMovie };
