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

export { DataProfile };
