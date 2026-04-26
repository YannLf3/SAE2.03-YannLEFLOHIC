let HOST_URL = "https://mmi.unilim.fr/~le-flohic4/SAE2.03-YannLEFLOHIC";
let DataProfile = {};

DataProfile.read = async function () {
  let answer = await fetch(HOST_URL + "/server/script.php?todo=readprofiles");
  let data = await answer.json();

  return data; // tableau d'bjets de la forme[{id: 1, name: "Yann", ...}, {...}, ...]
};

export { DataProfile };
