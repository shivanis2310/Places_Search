  var map = "";
  var marker = "";
  var openedId = "";
  var previousId = "";

  function mapOn(latitude,longitude,tdId){
  previousId = openedId;
  openedId = tdId;
  var directionsDisplay = new google.maps.DirectionsRenderer;
  var directionsService = new google.maps.DirectionsService;

  var tD = document.getElementById(tdId);
  console.log(tdId);
  var mD = document.getElementById('mapDiv');

  var initial = {lat: latitude, lng: longitude};
  map = new google.maps.Map(document.getElementById('mapDiv'), {
  zoom: 15,
  center: initial
});
marker = new google.maps.Marker({
position: initial,
map: map
}); 
directionsDisplay.setMap(map);
tD.appendChild(document.getElementById('content'));
tD.appendChild(mD);


if(mD.style.display == "block"){
if(previousId == openedId && previousId!= "")
mD.style.display = "none";
}

else 
mD.style.display = "block";

document.getElementById('walk').addEventListener('click', function() {
calculateAndDisplayRoute(directionsService, directionsDisplay,latitude,longitude,document.getElementById('walk').textContent);
});
document.getElementById('bike').addEventListener('click', function() {
calculateAndDisplayRoute(directionsService, directionsDisplay,latitude,longitude, document.getElementById('bike').textContent);
});
document.getElementById('drive').addEventListener('click', function() {
calculateAndDisplayRoute(directionsService, directionsDisplay,latitude,longitude, document.getElementById('drive').textContent);
});

}            


function calculateAndDisplayRoute(directionsService, directionsDisplay,latitude,longitude,text) {
console.log(directionsService);
console.log(directionsDisplay);
var selectedMode ="";
if(text == 'Walk There'){
selectedMode = 'WALKING';
}
else if(text == 'Drive There'){
selectedMode = 'DRIVING';

}

else
selectedMode = 'BICYCLING';

directionsService.route({
origin: {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>},  

destination: {lat: latitude, lng: longitude},  

travelMode: google.maps.TravelMode[selectedMode]
}, function(response, status) {
if (status == 'OK') {
directionsDisplay.setDirections(response);
} else {
window.alert('Directions request failed due to ' + status);
}
});
}



function initMap() {

var initial = {lat: 15, lng: 15};
map = new google.maps.Map(document.getElementById('mapDiv'), {
zoom: 15,
center: initial
});
marker = new google.maps.Marker({
position: initial,
map: map
});
}

