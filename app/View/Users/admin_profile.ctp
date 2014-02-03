<h1>Mon compte</h1>
<?php
	echo $this->Form->create('User');
		echo $this->Form->input('id');
		echo $this->Form->input('team_name',array('label'=>'Nom du responsable'));
		echo $this->Form->input('email',array('label'=>'Email'));
		echo $this->Form->input('mobile_phone',array('label'=>'Numéro de portable'));
		echo $this->Html->link('Changer mot de passe',array('action'=>'change_password','admin'=>false,'super'=>false));
	echo $this->Form->end('Enregistrer');
?>