<script>
	$(function() {
		$('#PasswordPassword').focus();
	});
</script>
<h2>Please fill in your password:</h2>
<div class="twinForm" style="width: 320px;">
	<?php echo $form->create('Password', array('url' => array('controller' => 'Todos', 'action' => 'login',$project_id,$name))) ?>
	<?php echo $form->password('password') ?>
	<?php echo $form->end('login') ?>
</div>
