let templateFile = await fetch("./component/MovieForm/template.html");
let template = await templateFile.text();

let MovieForm = {};

MovieForm.format = function (handler, categories) {
  // Construit les <option> à partir du tableau de catégories
  let options = '<option value="">-- Choisir une catégorie --</option>';
  for (let i = 0; i < categories.length; i++) {
    options += `<option value="${categories[i].id}">${categories[i].name}</option>`;
  }

  let html = template;
  html = html.replaceAll("{{handler}}", handler);
  html = html.replaceAll("{{options}}", options);
  return html;
};

export { MovieForm };
