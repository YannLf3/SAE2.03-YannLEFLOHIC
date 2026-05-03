let HOST_URL = "https://mmi.unilim.fr/~le-flohic4/SAE2.03-YannLEFLOHIC";

let DataComment = {};

DataComment.getByMovie = async function (id_movie) {
  let answer = await fetch(
    HOST_URL + "/server/script.php?todo=getcomments&id_movie=" + id_movie,
  );
  let data = await answer.json();
  return data;
};

DataComment.add = async function (id_profile, id_movie, content) {
  let formData = new FormData();
  // Action à effectuer sur le serveur
  formData.append("todo", "addcomment");
  // Identifiant du profil qui ajoute le commentaire
  formData.append("id_profile", id_profile);
  // Identifiant du film associé au commentaire
  formData.append("id_movie", id_movie);
  // Contenu du commentaire
  formData.append("content", content);

  let answer = await fetch(HOST_URL + "/server/script.php", {
    method: "POST",
    body: formData,
  });
  let data = await answer.json();
  return data;
};

export { DataComment };
