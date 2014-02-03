<ul class="breadcrumbs">
  <li>
	<?php
	if(AuthComponent::user('role')==='super'){
		echo $this->Html->link('Home', array('controller'=>'years','action' => 'index','super'=>true,'admin'=>false));
	}
	else{
		echo $this->Html->link('Home', array('controller'=>'years','action' => 'index','super'=>false,'admin'=>true));	
	}
	?>
  </li>
  <li class="unavailable"><?php echo $this->Html->link('Édition ' . $this->params["pass"]["0"],'#');?></li>
  <li>
  <?php
	if(AuthComponent::user('role')==='super'){
		echo $this->Html->link('Équipages', array('controller'=>'users','action' => 'index',$this->params["pass"]["0"],'super'=>true,'admin'=>false));
	}
	else{
		echo $this->Html->link('Équipages', array('controller'=>'users','action' => 'index',$this->params["pass"]["0"],'super'=>false,'admin'=>true));	
	}
	?>
  </li>
  <li class="unavailable"><?php echo $this->Html->link('Équipage ' . $this->data['User']['team_number'], '#');?></li>
  <li class="current"><?php echo $this->Html->link('Modifier', '#');?></li>
</ul>
<h1>Modifier équipage</h1>
<?php
	echo $this->Form->create('User');
		echo $this->Form->input('email',array('label'=>'Email'));
		echo $this->Form->input('team_name',array('label'=>'Nom equipe'));
		echo $this->Form->input('team_number',array('label'=>"Numéro d'équipage"));
		echo $this->Form->input('distance_penalty',array('label'=>"Pénalité (km)"));
		echo $this->Form->input('mobile_phone',array('label'=>'Numéro de portable'));
		echo $this->Form->input('member_name_one',array('label'=>"Nom du premier membre"));
		echo $this->Form->input('member_name_two',array('label'=>"Nom du second membre"));
		echo $this->Form->input('School.id');
		echo $this->Form->input('school_id',array('label'=>"Ecole représentée"));
		echo $this->Form->input('payed',array('label'=>"Inscription validée"));
		echo $this->Form->input('id',array('type'=>"hidden"));
	echo $this->Form->end('Enregistrer');
	if(AuthComponent::user('role')==='super'){
		echo $this->Html->link('Retour', array('controller'=>'users','action' => 'index',$this->params["pass"]["0"],'super'=>true,'admin'=>false));
	}
	else{
		echo $this->Html->link('Retour', array('controller'=>'users','action' => 'index',$this->params["pass"]["0"],'super'=>false,'admin'=>true));	
	}
?>