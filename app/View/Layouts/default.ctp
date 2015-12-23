<?php
/**
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
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'Sistema de Cobranza');
// $cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('style');
		echo $this->Html->script('jquery-2.1.1.min');
		
		/*bootstrap */
			echo $this->Html->css('bootstrap');
			echo $this->Html->css('bootstrap-theme.min');
			echo $this->Html->css('bootstrap-theme');
			echo $this->Html->script('bootstrap');
			echo $this->Html->script('npm');
		/* bootstrap */
		
		/*fonts */
			echo $this->Html->css('font-awesome/font-awesome.min');
		/* fonts */
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->Html->meta(
			'favicon.png',
			'/favicon.png',
			array('type' => 'icon')
		);
	?>
</head>
<body>
	<div id="container">
		<div id="header" class = "container-fluid">
			<?php
			echo $this->element('nav-bar'); 
			// echo $this->element('navs');
			?>
			
		</div>
		<div id="content" class = "container">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<!--<div id="footer">
			<div class="container">
				<p class="text-muted">Place sticky footer content here. <span class="glyphicon glyphicon-search" aria-hidden="true"></span></p>
			</div>
		</div> -->
	</div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>

<script>
	$( "#flashMessage" ).fadeOut( 5000 );
</script>
