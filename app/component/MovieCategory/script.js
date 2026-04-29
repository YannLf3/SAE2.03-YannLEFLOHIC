import { Movie } from "../Movie/script.js";

let templateFile = await fetch("./component/MovieCategory/template.html");
let template = await templateFile.text();

let MovieCategory = {};
MovieCategory.template = template;

MovieCategory.format = function (groupedMovies, favoriteIds) {
  let html = "";

  for (let categoryName in groupedMovies) {
    let films = groupedMovies[categoryName];
    let section = MovieCategory.template;
    section = section.replace("{{categoryName}}", categoryName);
    section = section.replaceAll(
      "{{movies}}",
      Movie.format(films, favoriteIds),
    );
    html += section;
  }

  return html;
};

export { MovieCategory };
