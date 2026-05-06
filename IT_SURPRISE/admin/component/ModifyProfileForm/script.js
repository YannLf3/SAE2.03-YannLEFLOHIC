let templateFile = await fetch("./component/ModifyProfileForm/template.html");
let template = await templateFile.text();

let ModifyProfileForm = {};

ModifyProfileForm.format = function (handler, profiles) {
  let options = '<option value="">--Choisir un profil--</option>';
  for (let i = 0; i < profiles.length; i++) {
    options += `<option value="${profiles[i].id}">${profiles[i].name}</option>`;
  }

  let html = template;
  html = html.replaceAll("{{handler}}", handler);
  html = html.replaceAll("{{options}}", options);
  return html;
};

export { ModifyProfileForm };
