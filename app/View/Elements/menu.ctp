<li><?php echo $this->Html->link('Accueil','/'); ?></li>
<li class="active"><?php echo $this->Html->link('Carte',array('controller'=>'positions','action'=>'index')); ?></li>
<li><?php echo $this->Html->link('Photos',array('controller'=>'positions','action'=>'photos')); ?></li>
<?php if(AuthComponent::user('id')): ?>
	<li><?php echo $this->Html->link("Mon compte",array('controller'=>'users','action'=>'profile')); ?></li>
	<li><?php echo $this->Html->link("Se déconnecter",array('controller'=>'users','action'=>'logout')); ?></li>
<?php else: ?>   
	<li><?php echo $this->Html->link("Se connecter",array('controller'=>'users','action'=>'login')); ?></li>   
	<li><?php echo $this->Html->link("S'inscrire",array('controller'=>'users','action'=>'signup')); ?></li>
<?php endif; ?>