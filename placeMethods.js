function sendPlace(place){

  var xhttp = new XMLHttpRequest();
  var url = ""
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      url= "place.php?parameter1=" + encodeURIComponent(place);

    }
  };
  xhttp.open("GET", url, true);
  xhttp.send();

}

function reviewOn() {
  if( document.getElementById("reviewtable").style.display == "none"){
    document.getElementById("reviewtable").style.display = "block";
    document.getElementById("showReview").innerHTML = "click to hide reviews";
    document.getElementById("reviewButton").value = "hide";    
    document.getElementById("phototable").style.display = "none";
    document.getElementById("showPhoto").innerHTML = "click to show photos";
    document.getElementById("photoButton").value = "show"; 
  }

  else{
   document.getElementById("reviewtable").style.display = "none";
   document.getElementById("showReview").innerHTML = "click to show reviews";
   document.getElementById("reviewButton").value = "show"; 

 }
}

function picOn() {
  if( document.getElementById("phototable").style.display == "none"){
    document.getElementById("phototable").style.display = "block";
    document.getElementById("showPhoto").innerHTML = "click to hide photos";
    document.getElementById("photoButton").value = "hide";    
    document.getElementById("reviewtable").style.display = "none";
    document.getElementById("showReview").innerHTML = "click to show reviews";
    document.getElementById("reviewButton").value = "show"; 
  }

  else{
   document.getElementById("phototable").style.display = "none";
   document.getElementById("showPhoto").innerHTML = "click to show photos";
   document.getElementById("photoButton").value = "show"; 

 }
}