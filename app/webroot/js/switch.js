//cescript ajoute des ecouteurs sur les boutons qui ont pour id switch1 et switch2. Lorsqu'un des 2 boutons est cliqué, il est activé et l'autre est désactivé

window.onload=function(){
    document.getElementById('switch1').addEventListener('click',activate,true);
    document.getElementById('switch2').addEventListener('click',activate,true);
};

function activate(event){
	document.getElementById('switch1').classList.remove('active');
	document.getElementById('switch2').classList.remove('active');
	event.target.classList.add('active');
	//cette ligne ajoute un message de chargement dans le div en_cours (optionelle)
	document.getElementById('en_cours').innerHTML = 'Chargement...';
}