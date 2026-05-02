let templateFile = await fetch("./component/MovieDetail/template.html");
let template = await templateFile.text();

let MovieDetail = {};
MovieDetail.template = template;

MovieDetail.format = function (movie) {
  if (!movie) {
    return "";
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
  html = html.replaceAll("{{average}}", "...");
  html = html.replaceAll("{{stars}}", "");
  return html;
};

//  le fetch du templateStar
let templateStarFile = await fetch("./component/MovieDetail/templateStar.html");
let templateStar = await templateStarFile.text();

MovieDetail.formatStars = function (id_movie, hasRated) {
  let starsHtml = "";
  for (let i = 1; i <= 5; i++) {
    let star = templateStar;
    star = star.replaceAll("{{value}}", i);
    star = star.replaceAll("{{id_movie}}", id_movie);
    // Si déjà noté, on désactive les étoiles
    star = star.replaceAll(
      "{{activeClass}}",
      hasRated ? "movie__detail__star--disabled" : "",
    );
    starsHtml += star;
  }
  return starsHtml;
};

export { MovieDetail };
