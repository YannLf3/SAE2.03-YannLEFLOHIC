let templateFile = await fetch("./component/ProfileSelector/template.html");
let templateCardFile = await fetch(
  "./component/ProfileSelector/templateCard.html",
);

let template = await templateFile.text();
let templateCard = await templateCardFile.text();

let ProfileSelector = {};

ProfileSelector.format = function (profiles) {
  let profilesHTML = "";

  for (let i = 0; i < profiles.length; i++) {
    let p = profiles[i];

    let avatarContent;

    if (p.avatar) {
      // Le profil a une image → on affiche l'image
      avatarContent = `<img src="${p.avatar}" alt="${p.name}" />`;
    } else {
      // Pas d'image → on affiche la 1ère lettre du nom à la place de l'image (fonction js charAt)
      avatarContent = p.name.charAt(0);
    }

    let card = templateCard;
    card = card.replaceAll("{{id}}", p.id);
    card = card.replaceAll("{{name}}", p.name);
    card = card.replaceAll("{{avatar}}", avatarContent);
    card = card.replaceAll("{{min_age}}", p.min_age);

    profilesHTML += card;
  }

  let html = template;
  html = html.replaceAll("{{profiles}}", profilesHTML);
  return html;
};

export { ProfileSelector };
