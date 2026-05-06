import { Movie } from "../Movie/script.js";

let templateFile = await fetch("./component/FeaturedMovies/template.html");
let template = await templateFile.text();

let FeaturedMovies = {};

FeaturedMovies.format = function (movies, favoriteIds = []) {
  if (movies.length === 0) {
    return false;
  }

  // Utilise le Movie.format() pour générer les mêmes cartes que le reste du site
  let cardsHtml = Movie.format(movies, favoriteIds);

  let html = template;
  html = html.replaceAll("{{movies}}", cardsHtml);
  return html;
};

export { FeaturedMovies };
