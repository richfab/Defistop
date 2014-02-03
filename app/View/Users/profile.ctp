<h1>Mon compte</h1>
<?php
	echo "Statut de l'inscription : ".($user['User']['payed'] ? '<span class="label label-success">validée</span>' : '<span class="label label-important">en cours</span>');
	echo $this->Form->create('User');
		echo $this->Form->input('id');
		echo $this->Form->input('team_name',array('label'=>'Nom equipe'));
		echo $this->Form->input('email',array('label'=>'Email'));
		echo $this->Form->input('mobile_phone',array('label'=>'Numéro de portable'));
		echo $this->Form->input('member_name_one',array('label'=>"Nom du premier membre"));
		echo $this->Form->input('member_name_two',array('label'=>"Nom du second membre"));
		echo $this->Form->input('School.id');
		echo $this->Form->input('school_id',array('label'=>"Ecole représentée"));
		echo $this->Html->link('Changer mot de passe',array('action'=>'change_password'));
	echo $this->Form->end('Enregistrer');
?>