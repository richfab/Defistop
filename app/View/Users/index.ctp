<?php foreach ($years as $year): ?>
	<h1>Equipages édition <?php echo $year['Year']['id']; ?></h1>
	<table class="table table-hover" id="user_table">
		<thead>
		    <tr>
		        <th><?php echo $this->Paginator->sort('team_number', 'Numéro'); ?></th>
		        <th><?php echo $this->Paginator->sort('distance', 'Distance (km)'); ?></th>
		        <th><?php echo $this->Paginator->sort('team_name', "Nom d'équipage"); ?></th>
		        <th><?php echo $this->Paginator->sort('member_name_one', 'Membre 1'); ?></th>
		        <th><?php echo $this->Paginator->sort('member_name_two', 'Membre 2'); ?></th>
		        <th><?php echo $this->Paginator->sort('School.name', 'Ecole'); ?></th>
		    </tr>
		</thead>
	
	<!-- Here's where we loop through our $posts array, printing out post info -->
		<tbody>
		    <?php foreach ($users as $user): ?>
		    	<?php if($user['User']['year_id'] == $year['Year']['id']): ?>
				    <tr>
				        <td>
				        	<?php echo $user['User']['team_number']; ?>
				        </td>
				        <td>
				        	<?php echo $user['User']['distance']; ?>
				        </td>
				        <td>
				            <?php echo $user['User']['team_name']; ?>
				        </td>
				        <td>
				            <?php echo $user['User']['member_name_one']; ?>
				        </td>
				        <td>
				            <?php echo $user['User']['member_name_two']; ?>
				        </td>
				        <td>
				            <?php echo $user['School']['name']; ?>
				        </td>
				    </tr>
				 <?php endif; ?>
			<?php endforeach; ?>
			<?php if(empty($users)): ?>
				<tr>
					<td>Aucun équipage</td><td></td><td></td><td></td><td></td><td></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
<?php endforeach; ?>