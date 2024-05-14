var mousePosX;
var mousePosY;
var replyBox = document.getElementById("reply");

document.onmousedown = (e) => {
    mousePosX = e.pageX;
    mousePosY = e.pageY;
}

function replyPopup() {
    
    replyBox.style.left = mousePosX;
    replyBox.style.top = mousePosY;
    replyBox.classList.toggle("hidden");
    
}