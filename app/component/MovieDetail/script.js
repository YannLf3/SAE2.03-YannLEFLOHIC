let templateFile = await fetch("./component/MovieDetail/template.html");
let template = await templateFile.text();

let MovieDetail = {};
MovieDetail.template = template;

MovieDetail.format = function (movie) {
  if (!movie) {
    return `<p class="movie__empty font-sans fs-size-base">Film introuvable.</p>`;
  }

  let html = MovieDetail.template;
  html = html.replaceAll("{{name}}", movie.name);
  html = html.replaceAll("{{image}}", movie.image);
  html = html.replaceAll("{{description}}", movie.description);
  html = html.replaceAll("{{category}}", movie.category);
  html = html.replaceAll("{{director}}", movie.director);
  html = html.replaceAll(
    "{{releaseYear}}",
    movie.releaseYear ?? movie.year ?? "",
  );
  html = html.replaceAll("{{min_age}}", movie.min_age);
  html = html.replaceAll("{{trailer}}", movie.trailer);
  return html;
};

export { MovieDetail };
