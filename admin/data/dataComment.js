let HOST_URL = "https://mmi.unilim.fr/~le-flohic4/SAE2.03-YannLEFLOHIC";

let DataComment = {};

DataComment.getPending = async function () {
  let answer = await fetch(
    HOST_URL + "/server/script.php?todo=getpendingcomments",
  );
  let data = await answer.json();
  return data;
};

DataComment.approve = async function (id) {
  let answer = await fetch(HOST_URL + "/server/script.php", {
    method: "POST",
    body: new URLSearchParams({ todo: "approvecomment", id: id }),
  });
  let data = await answer.json();
  return data;
};

DataComment.delete = async function (id) {
  let answer = await fetch(HOST_URL + "/server/script.php", {
    method: "POST",
    body: new URLSearchParams({ todo: "deletecomment", id: id }),
  });
  let data = await answer.json();
  return data;
};

export { DataComment };
