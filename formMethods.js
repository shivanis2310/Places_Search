   function resetForm(){
   document.getElementById('myForm').reset();

 }

 function checkRadio(){

 if(document.getElementById('here').checked)
 document.getElementById('otherloc').disabled = true;

 else if(document.getElementById('currentloc').checked) 
 document.getElementById('otherloc').disabled = false;


}

function myLocation() {

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function () {
if (this.readyState == 4 && this.status == 200) {
if(JSON.parse(xhttp.responseText)){
document.getElementById("Search").disabled = false;

var json = JSON.stringify(JSON.parse(xhttp.responseText));
document.getElementById('geoloc').setAttribute('value', json);

}
}
};
xhttp.open("GET", "http://ip-api.com/json/", true);
xhttp.send();

}