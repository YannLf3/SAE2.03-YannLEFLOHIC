let templateFile = await fetch("./component/Stats/template.html");
let templateCardFile = await fetch("./component/Stats/templateCard.html");
// Convertit les réponses en texte
let template = await templateFile.text();
let templateCard = await templateCardFile.text();

// Objet Stats qui contient la logique de formatage des statistiques
let Stats = {};

// Fonction qui formate les données statistiques en HTML
Stats.format = function (data) {
  // On définit les stats à afficher avec leur valeur et leur libellé
  // C'est ici que tu choisis quoi montrer et dans quel ordre
  let stats = [
    { value: data.total_profiles, label: "Profils créés" },
    { value: data.total_movies, label: "Films dans la base" },
    { value: data.avg_favorites, label: "Films favoris en moyenne par profil" },
    { value: data.most_favorited_movie, label: "Film le plus mis en favoris" },
    { value: data.most_popular_category, label: "Catégorie la plus populaire" },
  ];

  // Variable pour accumuler le HTML de toutes les cartes
  let cardsHtml = "";
  // Parcourt chaque statistique et génère une carte HTML
  for (let i = 0; i < stats.length; i++) {
    let card = templateCard;
    // Remplace les placeholders {{value}} et {{label}} par les vraies données
    card = card.replaceAll("{{value}}", stats[i].value);
    card = card.replaceAll("{{label}}", stats[i].label);
    // Ajoute la carte formatée à cardsHtml
    cardsHtml += card;
  }

  // Récupère le template principal
  let html = template;
  // Remplace le placeholder {{cards}} par toutes les cartes générées
  html = html.replaceAll("{{cards}}", cardsHtml);
  // Retourne le HTML complètement formaté
  return html;
};

export { Stats };
