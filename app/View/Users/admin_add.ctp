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
  <li class="current"><?php echo $this->Html->link('Ajouter', '#');?></li>
</ul>
<h1>Ajouter un équipage</h1>
<?php echo $this->Form->create('User'); ?>
    <?php
        echo $this->Form->input('email',array('label'=>'Email'));
        echo $this->Form->input('password',array('label'=>'Mot de passe'));
        echo $this->Form->input('password_confirm',array('label'=>'Confirmer mot de passe','type' => 'password'));
        echo $this->Form->input('team_name',array('label'=>"Nom d'équipe"));
        echo $this->Form->input('team_number',array('label'=>"Numéro d'équipage", 'value'=>$nextTeamNumber));
        echo $this->Form->input('mobile_phone',array('label'=>'Numéro de portable'));
        echo $this->Form->input('member_name_one',array('label'=>"Nom du premier membre"));
        echo $this->Form->input('member_name_two',array('label'=>"Nom du second membre"));
        echo $this->Form->input('School.id');
        //on selectionne l'ecole que l'admin gère
        echo $this->Form->input('school_id',array('label'=>"Ecole représentée",'selected' => AuthComponent::user('school_id')));
        echo $this->Form->input('year_id', array('label'=>'year','value'=>$this->params["pass"]["0"],'type'=>'hidden'));
    ?>
<?php 
	echo $this->Form->end(__('Ajouter'));
	if(AuthComponent::user('role')==='super'){
		echo $this->Html->link('Retour', array('controller'=>'users','action' => 'index',$this->params["pass"]["0"],'super'=>true,'admin'=>false));
	}
	else{
		echo $this->Html->link('Retour', array('controller'=>'users','action' => 'index',$this->params["pass"]["0"],'super'=>false,'admin'=>true));	
	}
?>