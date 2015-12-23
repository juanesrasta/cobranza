<div style="float:left"> 
	<table style="float:left">
		<tr>
			<td>Nombre</td>
			<td>
			<?php 
				echo $this->Form->input('Cobranza.NOMBRE',array(
					'label' => false,
					'id' => 'CobranzaNombre'
				));
			?>
			</td>
		</tr>
		<tr>
			<td>Gestor</td>
			<td>
			<?php
			echo $this->Form->input('gestore',array(
				'label' => false,
				'id' => 'GestorNombre'
			));
			?>
			</td>
		</tr>
	
		<tr> 
			<td>Cédula o RIF</td>
			<td>
			<?php
				echo $this->Form->input('Cobranza.CEDULAORIF',array(
					'label' => false,
					'id' => 'CobranzaCedulaorif',
					'disabled' => 'disabled'
				));
			?>
			</td>
		</tr>
	
		<!--<tr> 
			<td>Dirección</td>
			<td>
			<?php
			echo $this->Form->input('Data.direccion',array(
				'label' => false,
				'id' => 'DataDireccion',
				'type' => 'hidden'
			));?>
			</td>
		</tr> -->
	<!-- <tr>
		<td>Teléfono</td>
		<td>
		<?php
			// echo $this->Form->input('ClienGest.telefono',array(
				// 'label' => false,
				// 'id' => 'DeudorClienGestTelefono'
			// ));
		?>
		</td>
	</tr>
	<tr>
		<td>Asignado</td>
		<td>
		<?php
		// echo $this->Form->input('Cobranza.FECH_ASIG',array(
			// 'label' => false,
			// 'id' => 'CobranzaFechAsig',
			// 'type' => 'text'
		// ));
		?>
		</td>		
	</tr> -->
	
	<!--<tr>
		<td>Status</td>
		<td>
		<?php
			// echo $this->Form->input('Statu.condicion',array(
				// 'label' => false,
				// 'id' => 'StatuCondicion'
			// ));
		?>
		</td>
	</tr> -->

	<tr>
		<td>Próxima Gestión</td>
		<td>
		<?php
			echo $this->Form->input('ClienGest.proximag',array(
				'label' => false,
				'id' => 'ClienGestProximaG',
				'type' => 'text'
			));
		?>
		</td>
	</tr>

	<!--<tr>
		<td>Supervisor</td>
		<td>
		<?php
		// echo $this->Form->input('User.supervisor',array(
			// 'label' => false,
			// 'id' => 'UserSupervisor'
		// ));
		?>
		</td>
	</tr> -->
		<?php
		// echo $this->Form->input('ClienGest.id',array(
			// 'label' => false,
			// 'id' => 'ClienGestId',
			// 'type' => 'hidden'
		// ));
		?>

	</table>
</div>
	<div style="clear:both;">
<?php
	echo $this->Form->button('Guardar',array('type'=>'button','id'=>'button_deudor','style' => 'clear:both'));
?>
	</div>
<script>
	// $('#CobranzaFechAsig').datepicker({  // calendario Jquery
		// dateFormat: "dd-mm-yy",
	// });
	$('#ClienGestProximaG').datepicker({  // calendario Jquery
		dateFormat: "dd-mm-yy",
		beforeShowDay: disableSpecificDaysAndWeekends
	});
	
	function disableSpecificDaysAndWeekends(date) {
		var disabledSpecificDays = [];
		 
		<?php
			foreach ($dias_no_laborables as $f){ ?>
				disabledSpecificDays.push(<?php echo json_encode($f)?>);
			<?php }
		?>

		console.debug(disabledSpecificDays);
		// var m = date.getMonth();
		var m = ("0" + (date.getMonth() + 1)).slice(-2)
		// var d = date.getDate();
		var d = ("0" + date.getDate()).slice(-2);
		var y = date.getFullYear();
		
		var fecha = y + '-' + (m) + '-' + d;
		console.debug(fecha);
		
		
		for (var i = 0; i < disabledSpecificDays.length; i++) {
			if ($.inArray(fecha, disabledSpecificDays) != -1 || new Date() > date) {
				return [false];
			}
		}

		var noWeekend = $.datepicker.noWeekends(date);
		return !noWeekend[0] ? noWeekend : [true];
	}
	
	$('#button_deudor').click(function(){
		// id_deudor = $("#" ).val();
		nombre = $("#CobranzaNombre").val();
		// direccion = $("#DataDireccion").val();
		gestor = $("#GestorNombre").val();
		cedula = $("#CobranzaCedulaorif" ).val();
		proxima_g = $("#ClienGestProximaG" ).val();
		// telefono = $("#DeudorClienGestTelefono" ).val();
		// fecha_asig = $("#CobranzaFechAsig" ).val();		
		// status = $("#StatuCondicion").val();		
		// supervisor = $("#UserSupervisor").val();
		
		$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{nombre:nombre,cedula:cedula,gestor:gestor,proxima_g:proxima_g},
			// data: {nombre: 'a'},
			url:"<?php echo Router::url(array('action'=>'editar_deudor')); ?>",
			// url:"<?php echo Router::url(array('action'=>'editar_prueba')); ?>",
			success:function(data){
				console.debug(data);
				$('.deudor_nombre').text(data.nombre);
				$('.deudor_gestor').text(data.gestor);
				$('.deudor_supervisor').text(data.supervisor);
				// $('.deudor_direccion').text(data.direccion);
				$('.deudor_proxima_ges').text(data.proxima_g);
				$('#editar_datos_deudor').dialog("close");
			},error: function(textStatus,textStatus2, textStatus3) {
				alert("	error actualizando datos" + textStatus2 + textStatus3 );
			}
		});
	});
</script>