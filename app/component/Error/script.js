let templateFile = await fetch("./component/Error/template.html");
let template = await templateFile.text();

let Notif = {};

Notif.show = function (message) {
  let content = document.querySelector("#error");
  content.innerHTML = template.replaceAll("{{message}}", message);

  let closeBtn = content.querySelector(".error-popup__close");
  closeBtn.addEventListener("click", Notif.hide);

  setTimeout(Notif.hide, 4000);
};

Notif.hide = function () {
  let content = document.querySelector("#error");
  content.innerHTML = "";
};

export { Notif };
