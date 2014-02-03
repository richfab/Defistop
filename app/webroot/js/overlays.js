//variable globales
//l'objet json qui contient toutes les infos de la base de données recuperees par la requete xhtmlrequest
var resultat_json;

//couleurs : équipages qui terminent par 0-1-2-3-4-5-6-7-8-9
var line_colors = ["#87248f","#d74b9b","#91c841","#000000","#b6263f","#69b2c6","#5f7cbc","#f5dd34","#ea8f2c","#864a1d"];

//options pour l'apparence des parcours
var routeOptions = {strokeOpacity:0.9, strokeWeight:3};

//variable qui stocke les équipes avec leurs positions
var users = new Array();

//variables qui stocke les derniers marqueurs et leurs coordonnées pour les enlever et pour le bestfit
var last_markers = new Array();
var last_coords = new Array();

//variables qui stockent le parcours affiché et ses coordonnées pour les afficher et pour le bestfit (markers_to_show sont des marqueurs avec commentaire ou avec photo
var displayed_route;
var markers_to_show = new Array();

//variable qui stocke l'infowindow ouverte pour pouvoir fermer l'infowindow qui est ouverte avant d'en ouvrir une autre
var opened_infoWindow;

////////////*FONCTION DE RECUPERATION DES DONNEES*////////////
//fonction permettant de recuperer les infos dans la base de donnees puis d'actualiser la liste et afficher les parcours et dernieres positions
function get_json(bestfit, year, school_id, user_id){

	//on affiche le loader
	$('.loader').fadeIn('fast');
	
	$.ajax({
		type:"POST",
		url : '/defistop/positions/get_positions/',
		data : {
			year : year,
			school_id : school_id
		},
		dataType : 'json',
		success : function(data) {
			//une fois pret, on parse tous les resulats
			resultat_json = data;
			//on affiche toutes les equipes et leur parcours
			display_all_teams();
			//on recupere leur classement pour le mettre dans le page slide
			get_teams_sorted(user_id);
			//le parametre best fit permet d'activer le best fit (rechargement des resultats) ou non (mise a jour de la postion)
			if(bestfit){
				bestFit(last_coords);
			}
		},
		error : function(data) {
			alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
		},
		complete : function(data) {
			//on cache le loader
			$('.loader').fadeOut('slow');
		}
	});
}


////////////*FONCTION D'AFFICHAGE LORS DU CHARGEMENT DE LA PAGE*////////////
//fonction permettant d'afficher toutes les equipes avec leur parcours en tres pal et leur derniere position
function display_all_teams(){

	//on enleve toutes les formes de la carte
	removeAllMarkers();
	//on enleve tous les parcours de la carte
	removeAllRoutes();
	//on vide la liste
	$('#ul_ranking').empty();
	//on vide les objets
	users.length = 0;
	last_coords.length = 0;
	//on supprime le classement présent
	if(self_ranking.hasChildNodes()){
		self_ranking.removeChild(self_ranking.lastChild);
	}

	//si des resultats on ete retournes
	if(resultat_json.users.length != 0){
		
		//pour chaque equipe
		for(var i in resultat_json.users){
			
			var user_json = resultat_json.users[i];
			
			var user_current = new Object();
			user_current.id = user_json.User.id;
			user_current.team_name = user_json.User.team_name;
			user_current.team_number = user_json.User.team_number;
			user_current.member_name_one = user_json.User.member_name_one;
			user_current.member_name_two = user_json.User.member_name_two;
			user_current.distance = user_json.User.distance;
			user_current.school_name = user_json.School.name;
			user_current.positionsToShow = new Array();
			user_current.routeCoords = new Array();
			
			users.push(user_current);
			
			//le point de depart
			var position_current = new Object();
			position_current.lat = user_json.School.lat;
			position_current.lon = user_json.School.lon;
			position_current.type = "position";
			
			user_current.firstPosition = position_current;
			
			user_current.routeCoords.push(new google.maps.LatLng(position_current.lat, position_current.lon));
			
			//pour chaque coordonnee
			for(var j in user_json.Position){
			
				var position_current = new Object();
			
				position_current.lat = user_json.Position[j].lat;
				position_current.lon = user_json.Position[j].lon;
				position_current.photo_url = user_json.Position[j].photo_url;
				position_current.created = user_json.Position[j].created;
				position_current.commentaire = user_json.Position[j].commentaire;
				
				//si c'est un marqueur de photo
				if(position_current.photo_url != null){
					position_current.type = "photo";
				}
				//si c'est un marqueur de commentaire
				else if(position_current.commentaire != null && position_current.commentaire != ''){
					position_current.type = "commentaire";
				}
				//sinon
				else{
					position_current.type = "position";
				}
				
				//si c'est la derniere position
				if(j == user_json.Position.length-1){
					user_current.lastPosition = position_current;
				}
				else{
					//si c'est un commentaire ou une photo
					if(position_current.type == "photo" || position_current.type == "commentaire"){
						user_current.positionsToShow.push(position_current);
					}
				}
				
				//dans tous les cas on ajoute le point a la route
				user_current.routeCoords.push(new google.maps.LatLng(position_current.lat, position_current.lon));
				
			}
			if(user_current.lastPosition != null){
				//on affiche un marqueur de derniere position
				addLastMaker(user_current);
			}
		}
	}
}

//fonction permettant de recuperer les equipes classees par distance et de les inserer dans la page
function get_teams_sorted(user_id){

	var ul_ranking = document.getElementById('ul_ranking');
	var self_ranking = document.getElementById('self_ranking');
	
	if(users.length){
	
		for(var i=0 in users){
		
			if(users[i].lastPosition != null){
				il_y_a = func_il_y_a(users[i].lastPosition.created);
			}
			else{
				il_y_a = "Jamais actualisé";
			}
			
			var rank = parseInt(i)+1;
		
			var newLi = document.createElement('li');
			ul_ranking.appendChild(newLi);
			newLi.innerHTML = "<a onclick='display_team_route_from_list("+i+");'><div id='ptr_div'><div class='sprite last"+users[i].team_number+"'></div></div><div id='info_div'><span class='team'>"+rank+". "+users[i].team_name+" "+users[i].distance+" Km</span><br/>Polytech "+users[i].school_name+"</br><span class='times'>"+il_y_a+"</span></div></a></a>";
			
			//pour le classement de l'équipe connectée
			if(user_id == users[i].id){
				var liSelfRanking = document.createElement('li');
				self_ranking.appendChild(liSelfRanking);
				liSelfRanking.innerHTML = "Mon classement : "+rank+"/"+users.length;
			}
		}
	}
	else{
		var newLi = document.createElement('li');
		ul_ranking.appendChild(newLi);
		newLi.innerHTML = "<a>Aucun équipage</a>";
	}
}


////////////*FONCTIONS D'AFFICHAGE DES PARCOURS*////////////
//fonction permettant d'afficher le parcours d'une equipe donnne avec comme parametre la couleur du trait, sa taille et son opacite. l'id equipe sert a la definition du className
function display_team_route(user,options){

	//si l'equipe n'a aucun marqueur de position
	if(user.routeCoords.length <= 1){
		return;
	}

	//on enleve la route affichée
	removeAllRoutes();
	//et les marqueurs de photo ou commentaire
	removeMarkersToSwho();

	var route = new google.maps.Polyline({
		path: user.routeCoords,
		geodesic: true,
		strokeColor: line_colors[user.team_number%10],
		strokeOpacity: options.strokeOpacity,
		strokeWeight: options.strokeWeight
	});

	//on stocke le parcours
	displayed_route = route;
	
	//on affiche toutes les positions qui sont photo ou commentaire
	addMarkersToShow(user);
		
	//on enleve et on remet le premier marqueur pour qu'il soit au premier plan
	user.lastMarker.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
	
	//on centre la carte sur le parcours
	bestFit(user.routeCoords);
	
	//on affiche le parcours
	route.setMap(map);
}

//prends l'index de user
function display_team_route_from_list(index){
	//on ferme le volet de droite
	$.pageslide.close();
	//on ferme l'infowindow
	closeInfoWindow();
	//on affiche le parcours
	display_team_route(users[index],routeOptions);
}

//fonction qui bestfit la carte a partir d'une liste de latlnt
function bestFit(coords){
	//si le tableau est vide on zoom sur la position initiale
	if(last_coords.length==0){
		map.setCenter(new google.maps.LatLng(47.4122,2.9275));
		map.setZoom(6);
	}
	else{
		//  Create a new viewpoint bound
		var bounds = new google.maps.LatLngBounds ();
		//  Go through each...
		for (var i = 0 in coords) {
		//  And increase the bounds to take this point
			bounds.extend (coords[i]);
		}
		//  Fit these bounds to the map
		map.fitBounds (bounds);
	}
}

//fonction qui permet d'afficher toutes les positions photos ou commentaire d'une équipe
function addMarkersToShow(user){
	for(var i=0 in user.positionsToShow){
		addMarker(user,user.positionsToShow[i]);
	}
}

//fonction qui permet d'ajouter un marqueur a partir d'un user et d'une position
function addMarker(user,position){
	
	var image = {
		url: '/defistop/img/markers/instant-sprite.png', //généré sur "instantsprite.com"
		//scaledSize est la taille du sprite total (ici c'est la taille du fichier original divisée par 1,5)
		scaledSize: new google.maps.Size(4500, 30),
		//size est la taille affichée du marqueur (ici c'est la taille d'un marqueur divisée par 1,5)
		size: new google.maps.Size(10, 10),
		// The origin for this image is 0,0. (pour les sprites)
		origin: new google.maps.Point(10*(user.team_number%10)+4400,0),
		// The anchor for this image is the base of the middle.
		anchor: new google.maps.Point(5,5)
	};
	
	//on definit la position du marqueur, on instancie le marqueur et on le stocke dans le tableau de dernieres positions
	var marker = new google.maps.Marker({
	    position: new google.maps.LatLng(position.lat,position.lon),
	    title: user.team_name,
	    icon: image
	});
	
	var infowindow = generateInfoWindow(position);
	
	google.maps.event.addListener(marker, 'click', function() {
		if(opened_infoWindow != null){
			opened_infoWindow.close();
		}
		infowindow.open(map,marker);
		opened_infoWindow = infowindow;
	});
	
	google.maps.event.addListener(marker, 'mouseover', function() {
		if(opened_infoWindow != null){
			opened_infoWindow.close();
		}
		infowindow.open(map,marker);
		opened_infoWindow = infowindow;
	});
	
	// To add the marker to the map
	marker.setMap(map);
	
	//on les stocke
	markers_to_show.push(marker);	
	
}

//fonction qui permet d'ajouter le dernier marqueur d'une equipe
function addLastMaker(user){

	var lastPosition = user.lastPosition;
	
	//on stocke les coords de la position pour le bestfit
	last_coords.push(new google.maps.LatLng(lastPosition.lat, lastPosition.lon));
	
	var image = {
		url: '/defistop/img/markers/instant-sprite.png', //généré sur "instantsprite.com"
		//scaledSize est la taille du sprite total (ici c'est la taille du fichier original divisée par 1,5)
		scaledSize: new google.maps.Size(4500, 30),
		//size est la taille affichée du marqueur (ici c'est la taille d'un marqueur divisée par 1,5)
		size: new google.maps.Size(22, 30),
		// The origin for this image is 0,0. (pour les sprites)
		origin: new google.maps.Point((22*(user.team_number-1)),0),
		// The anchor for this image is the base of the middle.
		//anchor: new google.maps.Point(0,0)
	};
	
	//on definit la position du marqueur, on instancie le marqueur et on le stocke dans le tableau de dernieres positions
	var marker = new google.maps.Marker({
	    position: new google.maps.LatLng(lastPosition.lat,lastPosition.lon),
	    title: user.team_name,
	    icon: image
	});
	
	//on genere le contenu de l'infowindow
	var infowindow = generateInfoWindow(lastPosition);
	
	//evenement au clique sur le marqueur de derniere position
	google.maps.event.addListener(marker, 'click', function() {
		if(opened_infoWindow != null){
			opened_infoWindow.close();
		}
		infowindow.open(map,marker);
		opened_infoWindow = infowindow;
		display_team_route(user,routeOptions);
	});
	
	// To add the marker to the map
	marker.setMap(map);
	
	//on le stocke
	last_markers.push(marker);
	user.lastMarker = marker;
}

//fonction qui genere l'infowindow pour une position donnee
function generateInfoWindow(position){

	//calcul de la difference de temps avec aujourd'hui et affichage sous la forme 'il y a x j'
	var il_y_a = func_il_y_a(position.created);
	
	var commentaire_p = '';
	var photo_p = '';
	
	if(position.commentaire != null){
		commentaire_p = '<p>'+position.commentaire+'</p>'
	}
	
	if(position.type == "photo"){
		photo_p = '<p><a href="/defistop/positions/photos"><img src="/defistop/img/photos/S_'+position.photo_url+'"></a></p>';
	}
	
	var content = '<div class="infoWindowContent">'+commentaire_p+photo_p+'<p><small>'+il_y_a+'</small></p></div>'
	
	var infowindow = new google.maps.InfoWindow({
		content: content,
		maxWidth: 300
	});
	
	return infowindow;
}

//fonction qui ferme l'infowindow d'ouvert
function closeInfoWindow(){
	if(opened_infoWindow != null){
		opened_infoWindow.close();
		opened_infoWindow = null;
	}
}

//fonction qui efface tous les marqueurs de la carte (dernier et intermédiares)
function removeAllMarkers(){	
	removeLastMarkers();
	removeMarkersToSwho();
}

//fonction qui efface les derniers marqueurs
function removeLastMarkers(){
	while(last_markers.length){
	    last_markers.pop().setMap(null);
	}
}

//fonction qui efface les marqueurs intermediaires
function removeMarkersToSwho(){
	while(markers_to_show.length){
	    markers_to_show.pop().setMap(null);
	}
}

//fonction qui efface le parcours qui est affiché sur la carte
function removeAllRoutes(){
	if(displayed_route != null){
		displayed_route.setMap(null);
		displayed_route = null;
	}
}