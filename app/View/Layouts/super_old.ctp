<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.pg-opensource.org/licenses/mit-license.php MIT License
 */

$websiteName = 'Défistop';
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $websiteName ?>:
		<?php echo $title_for_layout; ?>
	</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css(array('cake.generic.modif','bootstrap.min','bootstrap-glyphicons','foundation','style','loader','mapquest_firefox_fix','jquery.pageslide'));
		
		echo $this->Html->script(array('jquery-1.10.2'));
		
		//on inclut les fichiers js qui sont spécifiques a une vue
		if(isset($jsIncludes)){
		    echo $this->Html->script($jsIncludes);
		}
		
		//on inclut les fichiers css qui sont spécifiques a une vue
		if(isset($cssIncludes)){
			echo $this->Html->css($cssIncludes);
		}

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<div id="offset" class="container-narrow">
				<!--Top bar responsive mobile-->
				<div id="topbar1">
					<?php echo $this->Html->image('bar-title-i.png', array('alt' => 'Logo','class'=>'center')); ?>
				</div>
	
				<!--Logo-->
				<div class="jumbotron">
					<?php echo $this->Html->image('logo-petit-i.png', array('alt' => 'Logo')); ?>
					<span class="label label-important">Organisateur</span>
				</div>
				
				<div class="masthead">
					<!--Bouton responsive mobile-->
					<a class="pg-open" href="#nav">Menu</a>
					<?php if(($this->params['controller']=='positions')&&($this->params['action']=='index')): ?>
					<a class="pg-openusr" href="#userlist">Classement</a>
					<?php endif; ?>
					
					<!--Menu-->
					<ul id="nav" class="nav">
					
						<li><?php echo $this->Html->link('Accueil','/'); ?></li>
						<li><?php echo $this->Html->link('Carte',array('controller'=>'positions','action'=>'index','super'=>false)); ?></li>
						<li><?php echo $this->Html->link('Photos',array('controller'=>'positions','action'=>'photos','super'=>false)); ?></li>
						<li><?php echo $this->Html->link('Organisation',array('controller'=>'years','action'=>'index','super'=>true)); ?></li>
						<?php if(AuthComponent::user('id')): ?>
						<li><?php echo $this->Html->link("Mon compte",array('controller'=>'users','action'=>'profile','admin'=>true,'super'=>false)); ?></li>
                        <li><?php echo $this->Html->link("Se déconnecter",array('controller'=>'users','action'=>'logout','super'=>false)); ?></li>
                        <?php else: ?>   
                        <li><?php echo $this->Html->link("Se connecter",array('controller'=>'users','action'=>'login')); ?></li>   
                        <li><?php echo $this->Html->link("S'inscrire",array('controller'=>'users','action'=>'signup')); ?></li>
                        <?php endif; ?>
						<li><?php echo $this->Html->link('A propos','/about'); ?></li>
					</ul>  	
				</div>
			</div>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		
		</div>
	</div>
	<?php 
		echo $this->Js->writeBuffer(); // Écrit les scripts en mémoire cache
	?>
	<?php
		echo $this->Html->script(array('jquery.pageslide.modif'));
	?>
	<script type="text/javascript">
		$(".pg-openusr").pageslide({ direction: "left"});
	</script>
	<script type="text/javascript">
		$(".pg-open").pageslide({ direction: "right"});
	</script>
	<?php
		echo $this->Html->script(array('foundation.min'));
	?>
	<script>
		$(document).foundation();
	</script>
</body>
</html>
