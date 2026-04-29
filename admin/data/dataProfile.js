let HOST_URL = "https://mmi.unilim.fr/~le-flohic4/SAE2.03-YannLEFLOHIC";

let DataProfile = {};

DataProfile.add = async function (formData) {
  let answer = await fetch(HOST_URL + "/server/script.php?todo=addprofile", {
    method: "POST",
    body: formData,
  });
  let data = await answer.json();
  return data;
};

DataProfile.read = async function () {
  let answer = await fetch(HOST_URL + "/server/script.php?todo=readprofiles");
  let data = await answer.json();
  console.log("Profils retournés par le serveur:", data); //pour debug le souci du profil anaé (tous publics)

  return data; // tableau d'bjets de la forme[{id: 1, name: "Yann", ...}, {...}, ...]
};

DataProfile.modify = async function (id, name, avatar, min_age) {
  let formData = new FormData();
  formData.append("id", id); //append même fonctionnement que sur pyhton pour ajouter des éléments à un objet formData
  formData.append("name", name);
  formData.append("avatar", avatar ?? "");
  formData.append("min_age", min_age);

  let answer = await fetch(HOST_URL + "/server/script.php?todo=modifyprofile", {
    method: "POST",
    body: formData,
  });

  let data = await answer.json();
  return data;
};
export { DataProfile };
