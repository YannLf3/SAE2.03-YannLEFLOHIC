let templateFile = await fetch("./component/Featured/template.html");
let templateCardFile = await fetch("./component/Featured/templateCard.html");
let template = await templateFile.text();
let templateCard = await templateCardFile.text();

let FeaturedSearch = {};

FeaturedSearch.format = function (movies) {
  let cardsHtml = "";

  for (let i = 0; i < movies.length; i++) {
    let card = templateCard;
    card = card.replaceAll("{{id}}", movies[i].id);
    card = card.replaceAll("{{name}}", movies[i].name);
    card = card.replaceAll("{{category_name}}", movies[i].category_name);
    card = card.replaceAll(
      "{{checkedAttr}}",
      movies[i].featured == 1 ? "checked" : "",
    );
    cardsHtml += card;
  }

  let html = template;
  html = html.replaceAll("{{results}}", cardsHtml);
  return html;
};

export { FeaturedSearch };
