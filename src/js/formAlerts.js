var alertbox = document.getElementById("form-alert");

function formAlert(msg) {
   alertbox.innerText = "";
   alertbox.innerText = msg;
   
   // hide after some time
   setTimeout(function(){
        alertbox.innerText = "";
  },5000)
  
}