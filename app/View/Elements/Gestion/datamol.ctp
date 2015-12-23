<fieldset>
	<legend> Datamol </legend>
	<div class = "left" style="height: 130px;overflow-y: scroll;width: 183px;">
		<table class="table_telefonos_datamol">
			<tr>
				<th>Teléfono</th>
				<th>Status</th>
			</tr>
			<?php
			if (!empty($telefonos_data_mol)) {
				foreach($telefonos_data_mol as $t) {
				?>
					<tr name="<?php echo $t['Datatel']['CedulaOrif']?>-<?php echo $t['Datatel']['Telefono'] ?>">
						<td><?php echo $t['Datatel']['Telefono'] ?></td>
						<td class="status_telefono_datamol"><?php echo $t['Datatel']['status'] ?></td>
					</tr>
				<?php
				}
			}
			?>
		</table>
	</div>
	<div class = "right" style = " position: relative; top: -10px; width: 50%;">
		<?php
		if (!empty($direccion_data_mol['Data']['direccion'])) {
			$dir = $direccion_data_mol['Data']['direccion'];
		} else {
			$dir = '';
		}
		?>
		<label for="Direccion">Dirección</label>
		<textarea name="" class="large_input direccion_datamol" style="resize: none; overflow-y: scroll; width: 182px;" cols="30" rows="6" id="Direccion"><?php echo $dir;?></textarea>
		
		<?php
			/*echo $this->Form->input('Status',array(
			'options' => $status_data_mol,
			'value' => '',
			'class' => 'form-control status_direccion_datamol',
		));*/
		?>
		<label>Status</label>
		<select name="dataMol" id="dataMol">
			<option value=""></option>
			<?php 
				for($i=0; $i<count($status_data_mol); $i++){?>
					<option value="<?=$i+1;?>"><?=utf8_encode($status_data_mol[$i]);?></option>
			<?php	} ?>
		</select>
	</div>
</fieldset>
<div id="validar_telefono" style="display:none">
	<span>Este Teléfono es valido?</span>
	<br><br>
	<span style="font-weight:bold" id="numero_telefono"></span>
	<span id="cedula_telefono"></span>
	<br><br>
	<?php 
	echo $this->Form->button('Si',array('type'=>'button','id'=>'boton_si_telefono'));
	echo $this->Form->button('No',array('type'=>'button','style'=>'margin-left: 15px;margin-right: 15px;','id'=>'boton_no_telefono'));
	echo $this->Form->button('Cancelar',array('type'=>'button','id'=>'boton_cancelar_telefono'));
	?>
</div>
<script>
$(".table_telefonos_datamol").delegate('tr','click', function(){
	//Lo pongo en amarillo
	$(".table_telefonos_datamol tr").removeClass('seleccionado'); 
	$(this).addClass('seleccionado');
	
	//Separo la cedula y el numero 
	cedula_telefono = $(this).attr("name").split("-");
	cedula = cedula_telefono[0];
	telefono = cedula_telefono[1];
	
	$('#numero_telefono').html(telefono);
	$('#numero_telefono').attr('name',telefono);
	$('#cedula_telefono').attr('name',cedula);
	
	$('#validar_telefono').dialog();
});

$('#boton_cancelar_telefono').click(function(){
	$('#validar_telefono').dialog("close");
});

$('#boton_si_telefono').click(function(){
	//Si la info es valida
	actualizar_status_telefono($('#numero_telefono').attr('name'),$('#cedula_telefono').attr('name'),'Si');
});

$('#boton_no_telefono').click(function(){
	//Si la info no es valida
	actualizar_status_telefono($('#numero_telefono').attr('name'),$('#cedula_telefono').attr('name'),'No');
});

function actualizar_status_telefono(telefono,cedula,status) {
	$.ajax({
		type:'POST',
		dataType:'JSON',
		data:{cedula:cedula,telefono:telefono,status:status},
		url:"<?php echo Router::url(array('action'=>'actualizar_status_telefono')); ?>",
		success:function(data){	
			//Actualizo la tabla
			$( ".table_telefonos_datamol" ).find( "tr.seleccionado td.status_telefono_datamol").html(data);
			$('#validar_telefono').dialog("close");
		},error: function() {
			alert("error_datamol_actualizar");
		}
	});
}

function cargar_datamol(cedula) {
	$.ajax({
		type:'POST',
		dataType:'JSON',
		data:{cedula:cedula},
		url:"<?php echo Router::url(array('action'=>'info_datamol')); ?>",
		success:function(data){	
			info_telefonos_datamol = '<tr><th>Teléfono</th><th>Status</th></tr>';
			$.each(data.telefonos_data_mol,function(i,v){
				info_telefonos_datamol += '<tr name="'+cedula+'-'+v.Datatel.Telefono+'">';
				info_telefonos_datamol += '<td>'+v.Datatel.Telefono+'</td>';
				info_telefonos_datamol += '<td class="status_telefono_datamol">'+v.Datatel.status+'</td>';
				info_telefonos_datamol += '</tr>';
			});
			$('.table_telefonos_datamol').html(info_telefonos_datamol);
			
			$('.direccion_datamol').html(data.direccion_data_mol.Data.Direccion);
			$('.direccion_datamol').attr('name',cedula);
			$('.status_direccion_datamol').val(data.direccion_data_mol.Data.Status);
		},error: function() {
			alert("error_datamol_telefono");
		}
	});
}

//Actualizar status de la direccion del datamol
$('.status_direccion_datamol').change(function(){
	cedula = $('.direccion_datamol').attr('name');
	status = $(this).val();
	$.ajax({
		type:'POST',
		dataType:'JSON',
		data:{cedula:cedula,status:status},
		url:"<?php echo Router::url(array('action'=>'actualizar_status_direccion__datamol')); ?>",
		success:function(data){	
		},error: function() {
			alert("error_datamol_direccion");
		}
	});
});
</script>