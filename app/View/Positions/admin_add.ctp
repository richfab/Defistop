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
  <li class="unavailable"><?php echo $this->Html->link('Équipage ' . $user['User']['team_number'], '#');?></li>
  <li><?php echo $this->Html->link('Trajet', array('controller'=>'positions','action' => 'index',$this->params["pass"]["0"],$this->params["pass"]["1"]));?></li>
  <li class="current"><?php echo $this->Html->link('Ajouter', '#');?></li>
</ul>
<h1>Ajouter une position</h1>
<?php echo $this->Form->create('Position'); ?>
        <?php
	        echo $this->Form->input('created',array('label'=>'Date et heure','type'=>'datetime','timeFormat'=>'24','dateFormat' => 'DMY'));
	        echo $this->Form->input('lieu',array('label'=>'Lieu'));
	        echo $this->Form->input('commentaire',array('label'=>'Commentaire','type'=>'textarea'));
	        echo $this->Form->input('from_lat', array('label'=>'from_latitude','value'=>$school['School']['lat'],'type'=>'hidden'));
	        echo $this->Form->input('from_lon', array('label'=>'from_longitude','value'=>$school['School']['lon'],'type'=>'hidden'));
	        echo $this->Form->input('lat', array('label'=>'to_latitude','type'=>'hidden'));
	        echo $this->Form->input('lon', array('label'=>'to_longitude','type'=>'hidden'));
	        echo $this->Form->input('distance', array('label'=>'distance','type'=>'hidden'));
	        echo $this->Form->input('user_id', array('label'=>'id equipe','type'=>'hidden'));
	    ?>
<?php 
	echo $this->Form->end(__('Ajouter')); 
	echo $this->Html->link('Retour', array('controller'=>'positions','action' => 'index',$this->params["pass"]["0"],$this->params["pass"]["1"]));?>