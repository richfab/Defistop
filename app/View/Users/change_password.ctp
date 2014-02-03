<h1>Changer mot de passe</h1>
<?php 
	echo $this->Form->create('User'); 
		echo $this->Form->input('password',array('label'=>'Nouveau mot de passe'));
		echo $this->Form->input('password_confirm',array('label'=>'Confirmer mot de passe','type' => 'password'));	
	echo $this->Form->end(__('Enregistrer'));
	//selon le role on choisi le type de retour
	if(AuthComponent::user('role')==='user'){
		echo $this->Html->link('Retour', array('controller'=>'users','action' => 'profile','super'=>false,'admin'=>false));
	}
	else{
		echo $this->Html->link('Retour', array('controller'=>'users','action' => 'profile','super'=>false,'admin'=>true));	
	}
?>