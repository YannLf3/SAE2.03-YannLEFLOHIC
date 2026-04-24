let templateFile = await fetch("./component/Movie/template.html");
let template = await templateFile.text();

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
Movie.format = function (films) {
  // Cas où aucun film n'est disponible
  if (films.length === 0) {
    // demander au prof si on a le droit de faire ça
    return `<p class="movie__empty font-sans fs-size-base">Aucun film disponible pour le moment.</p>`;
  }

  // On construit le HTML final en accumulant les cartes une par une
  let html = "";

  for (let i = 0; i < films.length; i++) {
    let card = Movie.template;
    // On remplace les placeholders par les vraies valeurs
    card = card.replaceAll("{{id}}", films[i].id);
    card = card.replaceAll("{{titre}}", films[i].titre);
    card = card.replaceAll("{{image}}", films[i].image);
    card = card.replaceAll("{{name}}", films[i].name); // pour le alt de l'image
    // On ajoute la carte au HTML final
    html = html + card;
  }

  return html;
};

export { Movie };
