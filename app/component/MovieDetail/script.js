let templateFile = await fetch("./component/MovieDetail/template.html");
let template = await templateFile.text();

let MovieDetail = {};
MovieDetail.template = template;

MovieDetail.format = function (movie) {
  let html = MovieDetail.template;
  html = html.replace("{{name}}", movie.name);
  html = html.replace("{{image}}", movie.image);
  html = html.replace("{{description}}", movie.description);
  html = html.replace("{{category}}", movie.category);
  html = html.replace("{{director}}", movie.director);
  html = html.replace("{{releaseYear}}", movie.releaseYear);
  html = html.replace("{{min_age}}", movie.min_age);
  html = html.replace("{{trailer}}", movie.trailer);
  return html;
};

export { MovieDetail };
