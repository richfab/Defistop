<div id="action_buttons">

	<?php
	//si l'utilisateur est connecte alors on affiche les boutons de mise a jour de position 
	if(AuthComponent::user('id')): ?>
		<?php 
		//si la course est en cours
		if($en_cours): ?>
			<?php
			//si l'utilisateur connecté est une equipe 
			if(AuthComponent::user('role')=='user'):?> 
				<?php
					echo $this->Form->create('Position');
						echo $this->Form->input('from_lat', array('label'=>'from_latitude','value'=>$school['School']['lat'],'type'=>'hidden'));
						echo $this->Form->input('from_lon', array('label'=>'from_longitude','value'=>$school['School']['lon'],'type'=>'hidden'));
						echo $this->Form->input('lat', array('label'=>'to_latitude','type'=>'hidden'));
				        echo $this->Form->input('lon', array('label'=>'to_longitude','type'=>'hidden'));
				        echo $this->Form->input('distance', array('label'=>'distance','type'=>'hidden'));
					echo $this->Form->end(); 
	        	?>
				<a id="map1" class="btn" onclick="upload_position()"><span id="maj_position" class="glyphicon glyphicon-map-marker"></a>
			<?php 
			//si l'utilisateur est un admin ou un super admin
			else: ?>
				<a id="map1" class="btn" onclick="alert('Les équipes peuvent utiliser ce bouton pour mettre à jour leur position.')"><span id="maj_position" class="glyphicon glyphicon-map-marker"></a>
			<?php endif; ?>	
		<?php 
		//si la course n'est pas en cours
		else: ?>
			<a id="map1" class="btn" onclick="alert('Utilisez ce bouton pour mettre à jour votre position une fois la course lancée.')"><span id="maj_position" class="glyphicon glyphicon-map-marker"></a>
		<?php endif; ?>
	<?php endif; ?>
	
	<div class="btn-group" id="school_selector">
	  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
	    <span id="school_title">École</span> <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu">
	  		<li>
	    		<a onclick="change_school_title('Toutes',null);get_values_for_json(true);"> Toutes </a>
	    	</li>
	  	<?php foreach ($schools as $school): ?>
	  		<li>
	    		<a onclick="change_school_title('<?php echo $school['School']['name'];?>', <?php echo $school['School']['id'];?> );get_values_for_json(true);"> <?php echo $school['School']['name'];?> </a>
	    	</li>
		<?php endforeach; ?>
	  </ul>
	</div>

	<div class="btn-group" id="year_selector">
	  <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
	    <span id="year_title">Année</span> <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu">
	  	<?php foreach ($years as $year): ?>
	  		<li>
	    		<a onclick="change_year_title( <?php echo $year['Year']['id'];?> );get_values_for_json(true);"> <?php echo $year['Year']['id'];?> </a>
	    	</li>
		<?php endforeach; ?>
	  </ul>
	</div>
	
	
	
</div>
<div id="map"></div>
<div id="userlist">
	<div class="hook" id="hook"></div>
	<h2 id='classement'>Classement</h2>
	<ul id="self_ranking"></ul>
	<ul id="ul_ranking"></ul>
</div>

<div class="loader">
    <span></span>
    <span></span>
    <span></span>
</div>

<div id="modalPhoto" class="reveal-modal" data-reveal>
	<div id="flashMessage" class="alert-box radius alert-success">Position mise à jour</div>
	<p class="lead">Un commentaire ou une photo à ajouter ?</p>
	<?php
		echo $this->Form->create('Position',array('type'=>'file'));
			echo $this->Form->input('photo_file',array('type'=>'file','label'=>'Prendre ou choisir une photo'));
			echo $this->Form->input('commentaire',array('label'=>'Ajouter un commentaire :','rows' => 3));
	?>
	<div id="submit">
		<button type="submit" class="btn btn-success">Envoyer</button><button type="button" class="btn btn-danger" onclick="$('#modalPhoto').foundation('reveal', 'close');">Fermer</button>
	</div>
	</form>
	<a class="close-reveal-modal" onclick="$('#modalPhoto').foundation('reveal', 'close');">&#215;</a>
</div>

<script>
	//cette fonction permet de remplacer 'Année' par l'année sélectionnée
	function change_year_title(year_id){
		document.getElementById('year_title').innerHTML = year_id;
		document.getElementById('year_title').selected = year_id;
	}
	//cette fonction permet de remplacer 'École' par l'école sélectionnée
	function change_school_title(school_name,school_id){
		document.getElementById('school_title').innerHTML = school_name;
		document.getElementById('school_title').selected = school_id;
	}
	//fonction qui recupere les parametres des selectors et les passe au json
	function get_values_for_json(bestFit){
	
		//si l'utilisateur est connecté on précise son id a la fonction get_json
		var user_id = null;
		<?php if(AuthComponent::user('id') != null): ?>
			user_id = <? echo AuthComponent::user('id'); ?>;
		<?php endif; ?>
	
		get_json(bestFit,document.getElementById('year_title').selected,document.getElementById('school_title').selected,user_id);
	}
	//si l'utilisateur est connecté on sélectionne son école dans la liste
	<?php if(AuthComponent::user('school_id') != null): ?>
		change_school_title('<? echo AuthComponent::user('School.name'); ?>',<? echo AuthComponent::user('school_id'); ?>);
	<?php endif; ?>
</script>