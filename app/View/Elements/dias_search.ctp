<fieldset>
	<legend> Busqueda </legend>
<?php
		echo $this->Form->create(null, array(
			'url' => array_merge(array('action' => 'index'), $this->params['pass'])
		));
	
		echo $this->Form->input('descripcion', array('label' => 'Descripción del día no laborable: ', 'empty' => 'Todos'));		
		echo $this->Form->input('fecha', array('label' => 'Fecha del día no laborable:  ', 'empty' => 'Todos','id' => 'pickDate','type' => 'text','readonly' => 'true'));		
		echo $this->Form->submit(__('Buscar'), array('div' => 'search_button', 'class' => 'boton_busqueda'));
		echo $this->Form->end();
?>
</fieldset>
<script>
$('#pickDate').datepicker({
    dateFormat: "dd-mm-yy",
});

</script>