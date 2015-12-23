<div style="float:left"> 
	<table style="float:left">
		<tr>
			<td>Teléfono:</td>
			<td>
			<?php 
				echo $this->Form->input('telefonoaux',array(
					'label' => false,
					'id' => 'telefono'
				));
			?>
			</td>
		</tr>
		<tr>
			<td>Ubicación:</td>
			<td>
			<?php
			echo $this->Form->input('ubicacion',array(
				'label' => false,
				'id' => 'ubicacion'
			));
			?>
			</td>
		</tr>
		
		<tr>
			<td>Dirección:</td>
			<td>
			<?php
			echo $this->Form->input('Telefono.diresccion',array(
				'label' => false,
				'id' => 'TelefonoDireccion'
			));
			?>
			</td>
		</tr>
		
			<?php
			echo $this->Form->input('Telefono.cedulaorif',array(
				'label' => false,
				'id' => 'cedulaDeudor',
				'type' => 'hidden'
			));
			?>
	
	
	</table>
</div>
	<div style="clear:both;">
<?php
	echo $this->Form->button('Guardar',array('type'=>'button','id'=>'button_agregar_telefono','style' => 'clear:both'));
?>
	</div>
<script>
	$('#button_agregar_telefono').click(function(){
		// id_deudor = $("#" ).val();
		telefono = $("#telefono").val();
		ubicacion = $("#ubicacion").val();
		direccion = $("#TelefonoDireccion").val();
		cedula = $("#cedulaDeudor").val();
		
		$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula:cedula,telefono:telefono,ubicacion:ubicacion,direccion:direccion},
			url:"<?php echo Router::url(array('action'=>'agregar_telefono_deudor')); ?>",
			success:function(data){
				// console.debug(data);
				deudor_info = $('.deudor_telefono').html();
				deudor_info += data.Telefono.telefono + ' - ' + data.Telefono.ubicacion + ' - ' + data.Telefono.direccion;
				$('.deudor_telefono').html(deudor_info);
				// console.debug('esto trata de mostar: '+ deudor_info);
				$('#agregar_telefono_deudor').dialog("close");
			},error: function(textStatus,textStatus2, textStatus3) {
				alert("	error actualizando datos" + textStatus2 + textStatus3 );
			}
		});
	});
</script>