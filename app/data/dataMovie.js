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

DataMovie.requestCategories = async function (age) {
  let answer = await fetch(
    HOST_URL + "/server/script.php?todo=readcategories&age=" + age,
  );
  let data = await answer.json();

  return data;
};

DataMovie.requestMovieDetail = async function (id) {
  // try : on met ici le code qui peut échouer (requête réseau, données mal formées, etc.)
  // Si une erreur est levée dans ce bloc, l'exécution passe automatiquement dans catch.
  try {
    // Appel API pour récupérer le détail d'un film via son id
    let answer = await fetch(
      HOST_URL + "/server/script.php?todo=readmoviedetail&id=" + id,
    );

    // Si le serveur répond autre chose que 200, on considère que c'est une erreur
    if (!answer.ok) {
      // On renvoie null pour indiquer au front qu'aucun détail exploitable n'est disponible
      return null;
    }

    // Conversion de la réponse HTTP en objet JavaScript
    let data = await answer.json();

    // Protection supplémentaire : réponse vide ou faux résultat côté API
    if (!data || data === false) {
      return null;
    }

    // Si une image existe, on fabrique son URL complète pour l'affichage
    if (data.image) {
      data.image = HOST_URL + "/server/images/" + data.image;
    }

    // Harmonisation des noms de champs attendus par l'interface
    data.titre = data.name;

    // On privilégie le nom de catégorie, sinon l'id de catégorie, sinon un texte par défaut
    data.category = data.category ?? data.id_category ?? "Non renseigné";

    // Retour de l'objet film prêt à être utilisé dans les composants
    return data;

    // catch : ce bloc s'exécute si une exception est levée dans try
    // (ex: problème réseau, JSON invalide, erreur inattendue).
    // Cela évite de casser l'application et permet de renvoyer une valeur contrôlée.
  } catch (error) {
    // Trace technique utile pour le débogage
    console.error("Erreur lors de la récupération du film :", error);

    // Valeur de repli uniforme en cas d'erreur
    return null;
  }
};

DataMovie.requestMoviesGroupedByCategory = async function (age) {
  let answer = await fetch(
    HOST_URL + "/server/script.php?todo=readmoviesgroupedbycategory&age=" + age,
  );

  let data = await answer.json();

  // data a la forme : { "Action": [film1, film2], "Comédie": [film3, ...], ... }
  // On parcourt chaque catégorie
  for (let categoryName in data) {
    // Pour chaque film de la catégorie courante
    for (let i = 0; i < data[categoryName].length; i++) {
      // Harmonisation du nom de propriété : name -> titre
      data[categoryName][i].titre = data[categoryName][i].name;

      // Construction de l'URL complète de l'image pour pouvoir l'afficher dans le front
      data[categoryName][i].image =
        HOST_URL + "/server/images/" + data[categoryName][i].image;
    }
  }

  // Renvoie l'objet final regroupé par catégorie, prêt à être affiché
  return data;
};

export { DataMovie };
