<ul class="breadcrumbs">
	<li><?php echo $this->Html->link('Home', array('controller'=>'years','action' => 'index'));?></li>
	<li class="unavailable"><?php echo $this->Html->link('Modifier école','#');?></li>
	<li class="current"><?php echo $this->Html->link($this->data['School']['name'],'#');?></li>
</ul>
<h1>Modifier responsable école</h1>
<?php
	echo $this->Form->create('User');
		echo $this->Form->input('team_name',array('label'=>'Nom du responsable'));
		$roles = array('super' => 'Organisateur', 'admin' => 'Responsable école');
		echo $this->Form->input('role', array('options' => $roles));
		echo $this->Form->input('email',array('label'=>'Email'));
		echo $this->Form->input('mobile_phone',array('label'=>'Numéro de portable'));
		echo $this->Form->input('School.lieu',array('label'=>'Adresse de départ'));
		echo $this->Form->input('School.lat',array('label'=>"Lat de départ",'type'=>'hidden'));
		echo $this->Form->input('School.lon',array('label'=>"Lon de départ",'type'=>'hidden'));
		echo $this->Form->input('id',array('type'=>"hidden"));
	echo $this->Form->end('Enregistrer');
	echo $this->Html->link('Retour', array('controller'=>'years','action' => 'index'));
?>