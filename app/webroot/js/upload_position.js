var icon_marker = "glyphicon glyphicon-map-marker";
var icon_ban_circle = "glyphicon glyphicon-ban-circle";
var icon_ok = "glyphicon glyphicon-ok";
var icon_not_ok = "glyphicon glyphicon-remove";

function upload_position() {

	//on affiche le loader
	$('.loader').fadeIn('fast');
	//on desactive le bouton
	$('#map1').attr("disabled","disabled");

	//si le navigateur gere la geo localisation
	if (navigator.geolocation)
	{
		//on recupere les coordonnees de position du peripherique
		navigator.geolocation.getCurrentPosition(position_successCallback, position_errorCallback);
	}
	else{
		alert("Votre navigateur ne prend pas en compte la géolocalisation");
	}
}

//si la geolocalisation a marché et a été acceptée
function position_successCallback(position){
	
	//on rempli les champs
	document.getElementById('PositionLat').value = position.coords.latitude;
	document.getElementById('PositionLon').value = position.coords.longitude;
	
	//on recupere la distance
    distance_get_geo();
	
	//on recupere le lieu au format texte
	get_lieu(position);
}

//si une erreur a ete detectee lors de la mise a jour de la position
function position_errorCallback(error){
	//l'icone de maj position devient une icone d'impossibilité
	document.getElementById("maj_position").className=icon_ban_circle;
	//on reactive le bouton
	$('#map1').removeAttr("disabled");
	//on cache le loader
	$('.loader').fadeOut('slow');
	//on affiche une alerte avec l'erreur
	switch(error.code){
		case error.PERMISSION_DENIED:
		  alert("Vous devez autoriser la localisation pour mettre à jour votre position");
		  break;      
		case error.POSITION_UNAVAILABLE:
		  alert("Votre position n'a pas pu être déterminée");
		  break;
		case error.TIMEOUT:
		  alert("Le service n'a pas répondu à temps");
		  break;
	}
}

//cette fonction recupere le lieu au format Ville, Pays grace a l'API google maps de reverse geocoding
function get_lieu(position){

	var lat = position.coords.latitude;
	var lon = position.coords.longitude;
	
	var lieu = "Inconnu";
	
	$.ajax({
		type:"GET",
		url : 'http://maps.googleapis.com/maps/api/geocode/json',
		data : {
			latlng : lat+","+lon,
			sensor : true,
			language : 'fr'
		},
		dataType : 'json',
		success : function(data) {
			//on recupere la ville et le pays
			var results = data.results;
			for(var i in results){
				if(results[i].types[0]=="locality"&&results[i].types[1]=="political"){
					lieu = results[i].formatted_address;
				}
			}
		},
		error : function(data) {
			//le lieu n'a pas pu être trouvé, il est noté inconnu (initialisé en début de fonction)
				
		},
		complete : function(data) {
			//on envoie les donnees a la bdd
			send_to_db(lat,lon,lieu);
		}
	});
}

//cette fonction envoie les donnees a la bdd
function send_to_db(lat,lon,lieu){
	
	var distance = document.getElementById('PositionDistance').value;
	
	$.ajax({
		type:"POST",
		url : '/defistop/positions/add',
		data : {
			lat : lat,
			lon : lon,
			lieu : lieu,
			distance : distance
		},
		dataType : 'json',
		success : function(data) {
			//l'icone de maj position rdevient l'icon ok
			document.getElementById("maj_position").className=icon_ok;
			//apres 5 sec il redevient l'icone normal
			setTimeout(function() {document.getElementById("maj_position").className=icon_marker;},5000);
			//on recharge toutes les données, on actualise la carte et on zoom sur la position mise a jour
			//le parametre false permet de dire qu'on ne veut pas appliquer le bestfit
			get_json(false);
			//on centre la carte sur le dernier marqueur et on applique le zoom
			maj_zoom(lat,lon);
			//partie uplaod de photo
			//on vide le modal des informations qu'il pouvait contenir
			$('#PositionCommentaire').val('');
			$('#PositionPhotoFile').val('');
			//on affiche le modal d'upload de photo
			$('#modalPhoto').foundation('reveal', 'open');
		},
		error : function(data) {
			//l'icone de maj position rdevient l'icon ok
			document.getElementById("maj_position").className=icon_not_ok;
			//apres 5 sec il redevient l'icone normal
			setTimeout(function() {document.getElementById("maj_position").className=icon_marker;},5000);
			alert($.parseJSON(data.responseText).errorMessage);
		},
		complete : function(data) {
			//on reactive le bouton
			$('#map1').removeAttr("disabled");
			//on cache le loader
			$('.loader').fadeOut('slow');
		}
	});
}