<div class="page-header" style = "text-align: center;">
	<h1> RECEPCION DE PAGOS <br>  <small></small></h1>
</div>

<div class = "search_box" style= "margin: 1%; width: 25%">
	<fieldset class = "gest_status_wrapper">
	<?php echo $this->Form->create('Data', array('type'=> 'file')); ?>
		<div class="" style="">	

			<div style="float: right"> Fecha: <?php echo date('d/m/Y'); ?> </div>	
		<div>		

		<div style="margin-top: 10px;"> Archivo: 
			<?php
				echo $this->Form->file('archivo',array(
					'label' => 'Selecciona un archivo'
				));
			?> 
		</div>  
		<div style="margin-top: 10px; margin-left: 40%; "> <?php echo $this->Form->end(__('Procesar')); ?> </div>
	</fieldset>

</div>