//fonction qui empeche la soumission du formulaire lors de l'appui sur la touche entrée (utile lors de la selection du lieu dans la liste)
function stopEnter(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type == "text")) {
            return false;
        }
    }
document.onkeypress = stopEnter;

function initialize() {
  
  var input = /** @type {HTMLInputElement} */(document.getElementById('SchoolLieu'));
  var schLat = /** @type {HTMLInputElement} */(document.getElementById('SchoolLat'));
  var schLon = /** @type {HTMLInputElement} */(document.getElementById('SchoolLon'));
  var autocomplete = new google.maps.places.Autocomplete(input);

  //la recherche est limitée aux lieux (pas les etablissements)
  autocomplete.setTypes(['geocode']);
  
  //la recherche est orientée sur l'europe
  var sw = new google.maps.LatLng(42.74701163184324, -4.7790531250000186);
  var ne = new google.maps.LatLng(51.67255469124495, 12.799071874999981);
  var bounds = new google.maps.LatLngBounds(sw,ne);
  autocomplete.setBounds(bounds);
  
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    
    var place = autocomplete.getPlace();
    //console.log(place.geometry.location);
    
    if (!place.geometry) {
      // empty poslat and poslon fields
      schLat.value = '';
      schLon.value = '';
      return;
    }

    // If the place has a geometry, then populate the poslat poslon fields
    if (place.geometry) {
      schLat.value = place.geometry.location.lat();
      schLon.value = place.geometry.location.lng();
    }

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }
  });
  
  //cette fonction permet d'empecher l'utilisateur de modifier a la main le lieu en vidant les champs poslat, poslon et poslieu eu moment ou l'utilisateur clique dans le champs poslieu pour le modifier
  input.onfocus = function() {
  	schLat.value = '';
    schLon.value = '';
    input.value = '';
  };
}

google.maps.event.addDomListener(window, 'load', initialize);