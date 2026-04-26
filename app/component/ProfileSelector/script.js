let templateFile = await fetch("app/component/ProfileSelector/template.html");
let template = await templateFile.text();

let ProfileSelector = {};

ProfileSelector.format = function (profiles) {
  let profilesHTML = "";
};
