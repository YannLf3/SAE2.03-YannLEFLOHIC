let templateFile     = await fetch("./component/CommentModeration/template.html");
let templateCardFile = await fetch("./component/CommentModeration/templateCard.html");
let template         = await templateFile.text();
let templateCard     = await templateCardFile.text();

let CommentModeration = {};

CommentModeration.format = function (comments) {
    if (!comments || comments.length === 0) {
        let html = template;
        html = html.replaceAll("{{comments}}", "<p class='moderation__empty'>Aucun commentaire à modérer pour le moment.</p>");
        return html;
    }

    let cardsHtml = "";
    for (let i = 0; i < comments.length; i++) {
        let card = templateCard;
        card = card.replaceAll("{{id}}", comments[i].id);
        card = card.replaceAll("{{profile_name}}", comments[i].profile_name);
        card = card.replaceAll("{{movie_name}}", comments[i].movie_name);
        card = card.replaceAll("{{created_at}}", comments[i].created_at);
        card = card.replaceAll("{{content}}", comments[i].content);
        // Statut lisible selon la valeur de approved
        let isApproved = comments[i].approved == 1;
        card = card.replaceAll("{{status}}", isApproved ? "Approuvé" : "En attente");
        card = card.replaceAll("{{statusClass}}", isApproved ? "moderation__card-status--approved" : "moderation__card-status--pending");
        cardsHtml += card;
    }

    let html = template;
    html = html.replaceAll("{{comments}}", cardsHtml);
    return html;
};

export { CommentModeration };