let templateFile = await fetch("./component/Movie/template.html");
let templateNewFile = await fetch("./component/Movie/templateNew.html");

let template = await templateFile.text();
let templateNew = await templateNewFile.text();

let Movie = {};
Movie.template = template;

/**
 * Movie.format
 *
 * Accepte un tableau de films et retourne une chaîne HTML
 * en remplaçant les placeholders du template par les vraies données.
 * Si le tableau est vide, affiche un message à l'utilisateur.
 *
 * @param {Array} films - Tableau d'objets films { id, titre, affiche }
 * @returns {string} HTML prêt à être injecté dans le DOM
 */
Movie.format = function (films, favoriteIds) {
  if (films.length === 0) {
    return "";
  }

  let html = "";

  for (let i = 0; i < films.length; i++) {
    let card = Movie.template;
    card = card.replaceAll("{{id}}", films[i].id);
    card = card.replaceAll("{{titre}}", films[i].titre);
    card = card.replaceAll("{{image}}", films[i].image);
    card = card.replaceAll("{{name}}", films[i].name);
    card = card.replaceAll(
      "{{newTag}}",
      films[i].is_new == 1 ? templateNew : "",
    );

    // ← ajouter ces deux lignes
    let isFavorite = false;
    for (let j = 0; j < favoriteIds.length; j++) {
      if (favoriteIds[j] == films[i].id) {
        isFavorite = true;
      }
    }
    card = card.replaceAll("{{checkedAttr}}", isFavorite ? "checked" : "");

    html = html + card;
  }

  return html;
};

export { Movie };
