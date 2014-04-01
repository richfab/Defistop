<?php echo $this->Session->flash('auth'); ?>


<div class="standalone-content-wrapper">
	<div class="inner-content-wrapper">
		<div class="standalone-content">
			<h2>Espace participants</h2>
			<p class="important-text"><strong>Se connecter</strong> au Défistop.</p>
			<?= $this->Form->create('User', array('class'=>'login-form', 'id'=>'login-form')); ?>
			<div class="input-fields">
			<?= $this->Form->input('email',array('label'=> false, 'div' => false, 'placeholder'=>"E-mail", 'class'=> 'form-field text-input login-email-field')); ?>
				<span class="password-input-wrapper">
					<?= $this->Form->input('password',array('label'=> false, 'div' => false, 'placeholder'=>"Mot de passe", 'class' => 'form-field password-input login-password-field text-input')); ?>
					<?= $this->Html->link('Oublié ?', array('action' => 'password'), array('class' => 'forgot-password-link')); ?>
				</span>
			</div>
			<hr>
			<div class="public-page-form-footer">
				<div class="form-submit">
					<button type="submit" class="btn">Se connecter</button>
				</div>
			</div>
			<?= $this->Form->end(); ?>
			<?= $this->Html->link("S'inscire", array('action' => 'signup'), array('id' => 'signup_link')); ?>
		</div>
	</div>
</div>