var disablerBox = document.getElementById("popups-toggler");
var headerNavPopup = document.getElementById("user-menu");
var newPostPopup = document.getElementById("create-post");
var newReplyPopup = document.getElementById("reply-post");
var popups = {
    'usrmenu': headerNavPopup,
    'newpost': newPostPopup,
    'newrepl': newReplyPopup
};
var popupsVals = Object.values(popups);

function toggPopups() {
    popupsVals.forEach((popup) => {
        if (popup !== null && popup.classList.contains('show')) {
            popup.classList.toggle('show');
        }
    });
    disablerBox.classList.toggle('show');
    disablerBox.classList.toggle('darkened');
}

function popupTogg(popup) {
    if (popup !== null) {
        popup.classList.toggle('show');
        disablerBox.classList.toggle('show');
        disablerBox.classList.toggle('darkened');
    }
    formAlert("");
}