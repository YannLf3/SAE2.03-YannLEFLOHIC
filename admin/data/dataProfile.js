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

DataProfile.modify = async function (formData) {
  let answer = await fetch(HOST_URL + "/server/script.php?todo=modifyprofile", {
    method: "POST",
    body: formData,
  });
  let data = await answer.json();
  return data;
};

export { DataProfile };
