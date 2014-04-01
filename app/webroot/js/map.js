var map;

function initialize() {
	// Enable the visual refresh
	google.maps.visualRefresh = true;

	var mapOptions = {
	  center: new google.maps.LatLng(47.4122,2.9275),
	  zoom: 6,
	  disableDefaultUI: true,
	  zoomControl: true,
	};
	map = new google.maps.Map(document.getElementById("map"),
	    mapOptions);
	    
	setMapSize();
	    
	window.onresize = function(event) {
		setMapSize();
	}
	
	google.maps.event.addListener(map, 'click', function() {
		closeInfoWindow();
	});
	
	//la position de map doit etre absolue pour que la carte s'affiche
	document.getElementById('map').style.position = "absolute";
	
	//on recupere les donnees de positions sans parametre d'année, la derniere année sera automatiquement récupérée dans la base de donnees
	get_values_for_json(true);
}

//fonction permettant de reperer le navigateur (msie ou non) et d'adapter la taille en fonction
function setMapSize(){
	//si la fenetre est inferieur a x alors on met la carte en largeur max (typiquement on est sur un smartphone)
	//le +1 sert a corriger un bug qui deplace la carte de plusieurs pixels (environ 20) sur la gauche
		
	var mapdiv = document.getElementById("map");
	var userlistdiv = document.getElementById("userlist");
	
	if(window.innerWidth < 769){
		var largeur_a_enlever_a_la_carte = 0;
		var hauteur_a_rajouter_a_la_liste = 51;
	}
	else{
		var largeur_a_enlever_a_la_carte = 290;
		var hauteur_a_rajouter_a_la_liste = 1;
	}
	//si le navigateur est internet explorer
	if (navigator.appName == "Microsoft Internet Explorer"){
		mapdiv.style.width = document.body.offsetWidth - 20 - largeur_a_enlever_a_la_carte;
		mapdiv.style.height = document.body.offsetHeight - $('#offset').height() - 20 - $("#footer").height();
		//meme chose pour la liste
		userlistdiv.style.height = document.body.offsetHeight - $('#offset').height() - 20 - $("#footer").height();
	} else {
		mapdiv.style.width = window.innerWidth - largeur_a_enlever_a_la_carte + 'px';
		mapdiv.style.height = window.innerHeight - $('#offset').height() -1 - $("#footer").height()  + 'px';
		//meme chose pour la liste
		userlistdiv.style.height = window.innerHeight - $('#offset').height() - $("#footer").height() + hauteur_a_rajouter_a_la_liste  + 'px';	
	}
}

//fonction permettant de zoomer sur une position donnee
function maj_zoom(lat_zoom,lon_zoom){
	map.setCenter(new google.maps.LatLng(lat_zoom,lon_zoom));
	map.setZoom(17);
}

window.onload=function(){

	//parce qu'on ne veut pas le style par defaut de cakephp pour cette page on enleve les padding:
	/*
document.getElementById('content').style.padding = 0;
	document.getElementById('footer').style.padding = 0;
*/
	
	//on lance la fonction d'initialisation
    initialize();
};