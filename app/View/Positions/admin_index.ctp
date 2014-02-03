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
  <li class="current"><?php echo $this->Html->link('Trajet', '#');?></li>
</ul>
<h1>Trajet</h1>
<p><?php echo $this->Html->link('Ajouter une position', array('action' => 'add',$this->params["pass"]["0"],$this->params["pass"]["1"]));?></p>
<table>
    <tr>
        <th><?php echo $this->Paginator->sort('created', 'Date'); ?></th>
        <th><?php echo $this->Paginator->sort('lieu', 'Lieu'); ?></th>
        <th><?php echo $this->Paginator->sort('commentaire', 'Commentaire'); ?></th>
        <th><?php echo $this->Paginator->sort('photo_url', 'Photo'); ?></th>
        <th>Actions</th>
    </tr>

<!-- Here's where we loop through our $posts array, printing out post info -->

    <?php foreach ($positions as $position): 
    $timestamp = strtotime($position['Position']['created']);?>
    <tr>
        <td><?php echo strftime("%d/%m/%y à %H:%M", $timestamp); ?></td>
        <td>
            <?php echo $position['Position']['lieu']; ?>
        </td>
        <td>
            <?php echo $position['Position']['commentaire']; ?>
        </td>
        <td>
        	<?php if ($position['Position']['photo_url']!=NULL) : 
	        	echo $this->Html->image("camera.png", array(
				    "alt" => "Camera",
				    'url' => '/img/photos/'.$position['Position']['photo_url'],
				    'target' => '_blank'
				));
        	endif; ?>
        </td>
        <td>
        	<?php echo $this->Html->link('Modifier', array('action' => 'edit', $this->params["pass"]["0"], $this->params["pass"]["1"], $position['Position']['id'])); ?>
            <?php echo $this->Form->postLink(
                'Supprimer',
                array('action' => 'delete', $this->params["pass"]["1"], $position['Position']['id']),
                array('confirm' => 'Etes vous sûr de vouloir supprimer cette position ?'));
            ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>
<p>
<?php echo $this->Paginator->numbers();	?>
</p>
<p>
<?php
	//selon le role on choisi le type de retour
	if(AuthComponent::user('role')==='super'){
		echo $this->Html->link('Retour', array('controller'=>'users','action' => 'index',$this->params["pass"]["0"],'super'=>true,'admin'=>false));
	}
	else{
		echo $this->Html->link('Retour', array('controller'=>'users','action' => 'index',$this->params["pass"]["0"],'super'=>false,'admin'=>true));	
	}
?>
</p>