<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
	<h3>Veuillez entrer votre login et mot de passe</h3>
        <?php 
        	echo $this->Form->input('email',array('label'=>'Email'));
        	echo $this->Form->input('password',array('label'=>'Mot de passe'));
        ?>
<?php echo $this->Form->end(__('Se connecter'));
	echo $this->Html->link('Mot de passe oublié', array('action'=>'password')); ?>
</div>