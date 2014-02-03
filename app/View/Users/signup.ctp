<h3>S'inscrire</h3>
<?php echo $this->Form->create('User'); ?>
    <?php
        echo $this->Form->input('email',array('label'=>'Email'));
        echo $this->Form->input('password',array('label'=>'Mot de passe'));
        echo $this->Form->input('password_confirm',array('label'=>'Confirmer mot de passe','type' => 'password'));
        echo $this->Form->input('team_name',array('label'=>"Nom d'équipage"));
        echo $this->Form->input('mobile_phone',array('label'=>'Numéro de portable'));
        echo $this->Form->input('member_name_one',array('label'=>"Nom du premier membre"));
        echo $this->Form->input('member_name_two',array('label'=>"Nom du second membre"));
        echo $this->Form->input('School.id');
        echo $this->Form->input('school_id',array('label'=>"Ecole représentée"));
    ?>
    	<a href="#" data-reveal-id="modalEmail">Mon école n'est pas dans la liste</a>
<?php echo $this->Form->end(__('Valider')); ?>
<div id="modalEmail" class="reveal-modal" data-reveal>
	<h2>Prévenir l'organisateur</h2>
	<p class="lead">Si votre école n'est pas dans la liste et que vous souhaitez qu'elle participe au Défistop, vous pouvez en faire la demande grâce à ce formulaire. L'organisateur en sera informé.</p>
	<?php
		echo $this->Form->create('School');
			echo $this->Form->input('name',array('label'=>'Indiquer le nom de votre école'));
		echo $this->Form->end('Envoyer');
	?>
	<a class="close-reveal-modal" onclick="$('#modalEmail').foundation('reveal', 'close');">&#215;</a>
</div>