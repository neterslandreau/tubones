<div id="userPanel" style="background: #fff; padding: 3px 0px 3px 0px;">
	<?php 
		if ($this->Session->check('Auth.User')) {
			echo $this->Html->link('Logout', array('controller' => 'AppUsers', 'action' => 'logout'));
		} else {
			echo $this->Html->link('Login', array('controller' => 'AppUsers', 'action' => 'login'));				
		}
	?>
</div>
