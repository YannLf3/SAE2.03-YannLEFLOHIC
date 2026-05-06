let templateFile = await fetch("./component/Comment/template.html");
let templateItemFile = await fetch("./component/Comment/templateItem.html");
let templateFormFile = await fetch("./component/Comment/templateForm.html");
let template = await templateFile.text();
let templateItem = await templateItemFile.text();
let templateForm = await templateFormFile.text();

let Comment = {};

Comment.format = function (comments, isConnected) {
  let commentsHtml = "";

  if (!comments || comments.length === 0) {
    commentsHtml =
      "<p class='comments__empty'>Aucun commentaire pour ce film. Soyez le premier à en laisser un !</p>";
  } else {
    for (let i = 0; i < comments.length; i++) {
      let item = templateItem;
      item = item.replaceAll("{{profile_name}}", comments[i].profile_name);
      item = item.replaceAll("{{created_at}}", comments[i].created_at);
      item = item.replaceAll("{{content}}", comments[i].content);
      commentsHtml += item;
    }
  }

  let html = template;
  html = html.replaceAll("{{comments}}", commentsHtml);
  // Si pas connecté, on n'affiche pas le formulaire
  html = html.replaceAll("{{form}}", isConnected ? templateForm : "");
  return html;
};

export { Comment };
