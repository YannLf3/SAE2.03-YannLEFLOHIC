let templateFile = await fetch("./component/NavBar/template.html");
let template = await templateFile.text();

let NavBar = {};

// On ajoute activeProfileName en deuxième paramètre
NavBar.format = function (hAbout, hFavorites, activeProfileName) {
  let html = template;
  html = html.replaceAll("{{hAbout}}", hAbout);
  html = html.replaceAll("{{hFavorites}}", hFavorites);
  html = html.replaceAll("{{activeProfileName}}", activeProfileName);
  return html;
};

export { NavBar };
