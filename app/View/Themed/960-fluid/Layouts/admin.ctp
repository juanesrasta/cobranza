<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('Sistema de Cobranza'); ?> - 
		<?php echo 'Sistema de Cobranza'; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		//echo $this->Html->css('cake.generic');
		echo $this->Html->css(array('reset', 'text', 'grid', 'layout', 'nav','style'));
		echo '<!--[if IE 6]>'.$this->Html->css('ie6').'<![endif]-->';
		echo '<!--[if IE 7]>'.$this->Html->css('ie').'<![endif]-->';
		echo $this->Html->script(array('jquery-1.3.2.min.js', 'jquery-ui.js', 'jquery-fluid16.js'));
		echo $scripts_for_layout;
		echo $this->Html->script('jquery-2.1.1.min');
		echo $this->Html->script('jquery.tooltipster.min');
		echo $this->Html->script('jquery-ui.min');
		echo $this->Html->script('tableExport');
		echo $this->Html->script('jquery.base64');
		echo $this->Html->css('tooltipster');

	?>
	 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	 <!--<link rel="stylesheet" href="/cobranza/app/webroot/css/bootstrap.css">
	 <link rel="stylesheet" href="/cobranza/app/webroot/css/bootstrap/webroot/css/bootstrap.min.css">-->

</head>
<body>
<div id="preload">

</div>
	<div id = "total_wrap" style="position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 99; background-color: black;  opacity: 0.6;display:none"> </div>
	<div class="container_16">			
		<div class="grid_16">
<!--			<h1 id="branding">-->
<!--				--><?php
//				echo $this->Html->image('Logo.jpg', array('height' => '100px'));
//				echo $this->Html->link(' Sistema de Gestión de Cobranza', array('controller' => 'index', 'action' => 'index'), array('style' => ' color: white; position: relative; top: -20px;')); ?>
<!--			</h1>-->
		</div>
		<div class="clear"></div>
		<div class="grid_16">
			 <?php echo $this->element('admin/main_menu'); ?>
		</div>
		
		<div class="clear" style="height: 10px; width: 100%;"></div>
		
			<?php echo $this->Session->flash(); ?>

			<?php echo $content_for_layout; ?>
		
		<div class="clear"></div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>

<script>
	$(document).ready(function() {
		$('.tooltip').tooltipster();
	});
</script>