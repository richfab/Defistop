<ul class="breadcrumbs">
  <li class="current"><?php echo $this->Html->link('Home', array('controller'=>'years','action' => 'index'));?></li>
</ul>
<h1>Equipages</h1>
<p>Sélectionner une année pour gérer les équipages</p>
<table>

<!-- Here's where we loop through our $year array, printing out post info -->

    <?php foreach ($years as $year): ?>
    <tr>
        <td>
            <?php echo $this->Html->link("Gérer l'édition ".$year['Year']['id'], array('controller'=>'users','action' => 'index',$year['Year']['id'])); ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

<?php 
	echo $this->Paginator->numbers();
?>

<h1>Ecoles participantes</h1>
<table class="table table-hover">
	<thead>
	    <tr>
	        <th>Polytech</th>
	        <th>Adresse de départ</th>
	        <th>Nom du responsable</th>
	        <th>Email</th>
	        <th>Numéro portable</th>
	        <th>Rôle</th>
	    </tr>
	</thead>

<!-- Here's where we loop through our $posts array, printing out post info -->
	<tbody>
	    <?php foreach ($users as $user): ?>
	    <tr>
	        <td>
	        	<?php echo $user['School']['name']; ?>
	        </td>
	        <td>
	        	<?php echo $user['School']['lieu']; ?>
	        </td>
	        <td>
	            <?php echo $user['User']['team_name']; ?>
	        </td>
	        <td>
	            <?php echo $user['User']['email']; ?>
	        </td>
	        <td>
	            <?php echo $user['User']['mobile_phone']; ?>
	        </td>
	        <td>
	            <?php echo ($user['User']['role']==='super' ? '<span class="label label-important">Organisateur</span>' : '<span class="label">Respo école</span>'); ?>
	        </td>
	    </tr>
	    <?php endforeach; ?>
	</tbody>
</table>

<?php 
	echo $this->Paginator->numbers();
?>