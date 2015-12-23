<div class="page-header" style = "text-align: center;">
	<h1> Cambio de Fecha (Próxima Gestión)<br>  <small></small></h1>
</div>


<div class = "search_box" style= "margin: 1%;">
	<fieldset class = "gest_status_wrapper">
		<fieldset class = 'gest_status'>
			<?php
				// debug($deudores);
			
				echo $this->Form->create(null, array(
			'url' => array_merge(array('controller' => 'gestion', 'action' => 'admin_cambio_fecha'), $this->params['pass'])
		));
				echo $this->Form->input('empresa', array('label' => 'Empresa ', 'empty' => 'Todas','id' => 'empresa_select'));
				echo $this->Form->input('gestore', array('label' => 'Gestor ', 'empty' => 'Todos','id' => 'gestore_select'));
				echo $this->Form->input('statu', array('label' => 'Status ', 'empty' => 'Todos','id' => 'statu_select'));				
				
			?>
		</fieldset>
		<div style = "float: left;">
			<fieldset style = "width: 300px;">
				<legend> Seleccione cuenta </legend>
				<table>
					<tr>
						<td style = "width: 100px;">
							<?php
								echo 'En atraso '.  $this->Form->checkbox('atraso', array('hiddenField' => false, 'id' => 'atraso_check'));
							?>
						</td>
						<td style = "text-align: center;" class = 'gestiones_info'>
							<?php
								echo " ";
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo "Del día ". $this->Form->checkbox('atraso', array('hiddenField' => false,'id' => 'del_dia_check')); ?>
						</td>
						<td style = "text-align: center;">
							<?php 
						echo $this->Form->input('fecha', array('label' => false, 'empty' => 'Todos','id' => 'pickDate2','type' => 'text','readonly' => 'false', 'class' => 'short_input', 'style' => 'display: none;'));
					?>
						</td>
					</tr>
				</table>
			</fieldset>
			<fieldset style = "width: 300px; float: left;">
				<legend> Indique Cantidad </legend>
				<table>
					<tr>
						<td style = "width: 100px;">
							<?php
								echo 'Todas '.  $this->Form->checkbox('Todas', array('hiddenField' => false, 'id' => 'todas_check'));
							?>
						</td>
						<td style = "text-align: center;">
							<?php
								echo 'Cantidad '.  $this->Form->checkbox('Cantidad', array('hiddenField' => false, 'id' => 'cantidad_check'));
								echo $this->Form->input('cantidad', array('label' => false, 'empty' => 'Todos','id' => 'cantidad','type' => 'text','readonly' => 'false', 'class' => 'short_input', 'style' => 'display: none;'));
							?>
						</td>
					</tr>
					
				</table>
			</fieldset>
		</div>
		<div style = "margin-left: 10px; float: left;">
			<table style = "width : 200px;">
				<tr>
					<td></td>
				</tr>
			</table>
		</div>
		<div style = "margin-left: 10px; float: left;">
			<table style = "width : 200px;">
				<tr>
					<td style = "text-align: center;">
						<?php echo "Fecha: " . date("j/ n/ Y"); ?>
					</td>
				</tr>
				<tr>
					<td style = "text-align: center;">Cambiar al día:</td>
				</tr>
				<tr>
					<td style = "text-align: center;">
						<?php
							echo $this->Form->input('ClienGest.fecha_cambio', array('label' => false, 'empty' => 'Todos','id' => 'pickDate1','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
						?>
					</td>
				</tr>
				<tr>
					<td style = "text-align: center;"><?php
							echo $this->Form->submit(__('Aceptar'));
					?></td>
				</tr>
			</table>
		</div>

</fieldset>

</div>

<script>
$('#pickDate1').datepicker({
    dateFormat: "dd-mm-yy",
});
$('#pickDate2').datepicker({
    dateFormat: "dd-mm-yy",
});
$('#pickDate3').datepicker({
    dateFormat: "dd-mm-yy",
});

$('#del_dia_check').change(function(){
	if(this.checked){
		$('#pickDate2').show();
		$('#atraso_check').attr('checked', false);
	}else{
		$('#pickDate2').hide();
	}
});

$('#atraso_check').change(function(){
	if(this.checked){
		$('#del_dia_check').attr('checked', false);
		$('#pickDate2').hide();
	}
});

$('#todas_check').change(function(){
	if(this.checked){
		$('#cantidad_check').attr('checked', false);
		$('#cantidad').hide();
	}else{
		$('#cantidad').hide();
	}
});

$('#cantidad_check').change(function(){
	if(this.checked){
		$('#cantidad').show();
		$('#todas_check').attr('checked', false);
	}else{
		$('#cantidad').hide();
	}
});

// when any element change, send the values and make a query through ajax

$('#cantidad_check,#todas_check,#atraso_check,#empresa_select,#gestore_select,#statu_select,#pickDate2').change(function(){
	$.ajax({
		type:'POST',
		dataType:'JSON',
		data:{gestor:$("#gestore_select").val(),empresa:$("#empresa_select").val(),status:$("#statu_select").val(),atraso:$("#atraso_check").is(':checked'),del_dia:$("#pickDate2").val()},
		url:"<?php echo Router::url(array('action'=>'cargar_info_cambio_fecha')); ?>",
		success:function(data){
			console.debug(data);				
			$('.gestiones_info').html(data);
		},error: function() {
			alert("error_cambio_fecha");
		}
	});
});
	
</script>