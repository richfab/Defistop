<ul class="breadcrumbs">
	<li><?php echo $this->Html->link('Home', array('controller'=>'years','action' => 'index'));?></li>
	<li class="unavailable"><?php echo $this->Html->link('Ajouter école','#');?></li>
</ul>
<h1>Ajouter une école</h1>
<?php echo $this->Form->create('User'); ?>
    <?php
        echo $this->Form->input('School.name',array('label'=>"Ville de l'école"));
    	echo $this->Form->input('team_name',array('label'=>"Nom du responsable"));
    	$roles = array('admin' => 'Responsable école','super' => 'Organisateur');
		echo $this->Form->input('role', array('options' => $roles));
        echo $this->Form->input('email',array('label'=>'Email du responsable'));
        echo $this->Form->input('mobile_phone',array('label'=>'Numéro de portable du responsable'));
		echo $this->Form->input('School.lieu',array('label'=>'Adresse de départ'));
		echo $this->Form->input('School.lat',array('label'=>"Lat de départ",'type'=>'hidden'));
		echo $this->Form->input('School.lon',array('label'=>"Lon de départ",'type'=>'hidden'));
        echo $this->Form->input('password',array('label'=>'Mot de passe du responsable'));
        echo $this->Form->input('password_confirm',array('label'=>'Confirmer mot de passe','type' => 'password'));
    ?>
<?php 
	echo $this->Form->end(__('Ajouter')); 
	echo $this->Html->link('Retour', array('controller'=>'years','action' => 'index'));
?>