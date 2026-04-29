let HOST_URL = "https://mmi.unilim.fr/~le-flohic4/SAE2.03-YannLEFLOHIC";

let DataFavorite = {};

DataFavorite.add = async function (id_profile, id_movie) {
  let answer = await fetch(
    HOST_URL +
      "/server/script.php?todo=addfavorite&id_profile=" +
      id_profile +
      "&id_movie=" +
      id_movie,
  );
  let data = await answer.json();
  return data;
};

DataFavorite.read = async function (id_profile) {
  let answer = await fetch(
    HOST_URL + "/server/script.php?todo=readfavorites&id_profile=" + id_profile,
  );
  let data = await answer.json();
  return data;
};

export { DataFavorite };
