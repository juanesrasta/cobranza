<div id="bloquea" style="width: 20%;height: 80px;overflow: hidden;z-index: 10000;position: relative;text-align: center;background: #FFFFFF;margin-left: auto;margin-right: auto;display:none">
	<?php echo $this->Html->image('enviando.gif',array('style' => 'margin-top:20px')) ?>
</div>
<div class="page-header" style = "text-align: center;">
	<h1> Anexo de Cartera<br>  <small></small></h1>
</div>
<div class = "search_box" style= "margin: 1%;">
	<fieldset>
	<?php 
	echo $this->Form->create(null, array('type' => 'file'));
	echo $this->Form->input('cliente_id',array('label' => 'Empresa'));
	echo $this->Form->file('archivo',array(
		'label' => 'Selecciona un archivo',
		'style' => 'margin-left:205px'
	));
	echo $this->Form->submit('Leer Archivos');
	?>
	</fieldset>
</div>


<script>
function activar_pantalla() {
	document.getElementById('total_wrap').style.display='block';
	document.getElementById('bloquea').style.display='block';
}
	
</script>