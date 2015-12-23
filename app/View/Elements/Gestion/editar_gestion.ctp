<div style="float:left"> 
<table style="float:left">
	<tr>
		<td>FechaReg</td>
		<td>
		<?php 
		echo $this->Form->input('ClienGest.fecha_reg',array(
			'label' => false,
			'id' => 'ClienGestFecha',
			'class' => 'pickDate_diasLaborables',
			'type' => 'text',
		));
		?>
		</td>
	</tr>
	<tr>
		<td>Teléfono</td>
		<td>
		<?php
		echo $this->Form->input('ClienGest.telefono',array(
			'label' => false,
			'id' => 'ClienGestTelefono'
		));
		?>
		</td>
	</tr>
	<tr>
		<td>Contacto</td>
		<td>
		<?php
		echo $this->Form->input('ClienGest.contacto',array(
			'label' => false,
			'id' => 'ClienGestContacto',
			'options' => $contactos
		));
		?>
		</td>
	</tr>
	<tr>
		<td>Producto</td>
		<td>
		<?php
		echo $this->Form->input('ClienGest.producto',array(
			'label' => false,
			'id' => 'ClienGestProducto',
			'type' => 'select'
		));?>
		</td>
	</tr>
	<tr>
		<td>Status</td>
		<td>
		<?php
		echo $this->Form->input('ClienGest.cond_deud',array(
			'label' => false,
			'id' => 'ClienGestStatus',
			'type' => 'select'
		));?>
		</td>
	</tr>
	<tr>
		<td>Próxima Gestión</td>
		<td>
		<?php
		echo $this->Form->input('ClienGest.proximag',array(
			'label' => false,
			'id' => 'ClienGestProximaGestion',
			'class' => 'pickDate_diasLaborables_mayorHoy',
			'type' => 'text',
		));?>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top">Comentario1 al cliente</td>
		<td>
		<?php
		echo $this->Form->input('ClienGest.observac',array(
			'label' => false,
			'id' => 'ClienGestObservac',
			'disabled' => true,
			'style' => 'float:left;margin-right:5px'
		));?>
		<div id="mensaje_PP" style="display:none">
			<?php
			echo $this->Form->input('ClienGest.fecha_pago',array(
				'label' => false,
				'id' => 'ClienGestFechaPago',
				'type' => 'text',
				'class' => 'pickDate_diasLaborables',
				'style'=> 'width:140px'
			));
			echo $this->Form->input('ClienGest.bolivares',array(
				'label' => false,
				'id' => 'ClienGestBolivares',
				'style' => 'width:100px'
			));?>
		</div>
		<div id="mensaje_MM" style="display:none">
			<?php
			echo $this->Form->input('ClienGest.nombre',array(
				'label' => false,
				'id' => 'ClienGestNombre',
				'style' => 'width:143px'
			));
			echo $this->Form->input('ClienGest.apellido',array(
				'label' => false,
				'id' => 'ClienGestApellido',
				'style' => 'width:143px'
			));
			echo $this->Form->input('ClienGest.parentesco',array(
				'label' => false,
				'id' => 'ClienGestParentesco',
				'style' => 'width:143px'
			));
			?>
		</div>
		</td>
	</tr>
	<tr>
		<td style="vertical-align:top">Comentario2 interno</td>
		<td>
		<?php
		echo $this->Form->input('ClienGest.Observac1',array(
			'label' => false,
			'id' => 'ClienGestObservac2',
			'type' => 'textarea',
		)); 
		echo $this->Form->input('ClienGest.editar',array(
			'label' => false,
			'id' => 'ClienGestEditar',
			'type' => 'hidden'
		));
		echo $this->Form->input('ClienGest.cedulaorif',array(
			'label' => false,
			'id' => 'ClienGestCedulaorif',
			'type' => 'hidden',
		));
		?>
		</td>
	</tr>
	</table>
	</div>
	<div style="float:right;text-align: center;margin-left: 30px;">
	GestXDia
	<table id="tabla_proximas_gestiones">
		<tr><th style="width:78px;text-align:center">Día</th><th>Cant</th></tr>
	</table>
	</div>
	<div style="clear:both">
<?php
	echo $this->Form->button('Guardar',array('type'=>'button','id'=>'button_gestion','style' => 'clear:both'));
?>
	</div>
<script>
//Hacer click en modificar o agregar gestion
$('.click_gestion').click(function(){
	$('#editar_gestion').dialog();
	$('#tabla_proximas_gestiones').html('<tr><th style="width:78px;text-align:center">Día</th><th>Cant</th></tr>');
	//Listo los productos por deudor seleccionado
	cedula_deudor = $( "#table_info_deudores" ).find( "tr.seleccionado").attr('name');
	boton = $(this).attr('name');
	listar_productos_por_deudor(cedula_deudor,boton);
	listar_telefonos_deudor(cedula_deudor);
	producto = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_producto").attr('name');
	listar_status(producto);
	$('#ClienGestFechaPago').val('');
	$('#ClienGestBolivares').val('');
	$('#ClienGestNombre').val('');
	$('#ClienGestApellido').val('');
	if ($(this).attr('name') == 'editar') { //Solo lleno los campos si se va a editar
		//Si se va a editar se llenan los campos
		fecha_reg = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_fecha_reg").attr('name');
		contacto = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_contacto").attr('name');
		status = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_cond_deud").attr('name');
		producto = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_producto").attr('name');
		proximag = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_proximag").attr('name');
		comentario2 = $(".comentario_seleccionado #input_comentario2" ).val();
		$("#ClienGestFecha" ).val(fecha_reg);
		$("#ClienGestProximaGestion" ).val(proximag);
		$("#ClienGestContacto" ).val(contacto);
		$("#ClienGestStatus" ).val(status);
		$("#ClienGestObservac2" ).val(comentario2);
		$("#ClienGestEditar" ).val($( ".tabla_gestiones" ).find( "tr.seleccionado").attr('name'));
		$("#ClienGestCedulaorif" ).val(0);
		
		//Busco las próximas gestiones 
		gestor = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_gestor").attr('name');
		$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{gestor:gestor,status:status,producto:producto},
			url:"<?php echo Router::url(array('action'=>'buscar_proximas_gestiones_y_comentario')); ?>",
			success:function(data){
				$.each(data.proximas_gestiones,function(i,v){
					proxima_gestion = '<tr>';
					proxima_gestion += '<td>'+v.ClienGest.proximag+'</td>';
					proxima_gestion += '<td>'+v[0]['numeroGestiones']+'</td>';
					proxima_gestion += '</tr>';
					$("#tabla_proximas_gestiones").append(proxima_gestion);
				});
				//Aprovecho y cargo el comentario
				$("#ClienGestObservac").val(data.comentario);
				if (status == 'PP' || status == 'MM') {
					$("#ClienGestObservac").width("95px");
					if (status == 'PP') {
						$("#mensaje_MM").hide();
						$("#mensaje_PP").show();
					} else {
						$("#mensaje_PP").hide();
						$("#mensaje_MM").show();
					}
				} else {
					$("#mensaje_MM").hide();
					$("#mensaje_PP").hide();
					$("#ClienGestObservac").width('auto');
				}
			},error: function() {
				alert("error_proximas_gestiones");
			}
		});
	} else {
		$("#ClienGestEditar" ).val(0);
		var f = new Date();
		$("#ClienGestFecha" ).val(f.getFullYear()+"-"+(parseInt(f.getMonth())+parseInt(1))+"-"+f.getDate());
		$("#ClienGestProximaGestion" ).val('');
		$("#ClienGestTelefono" ).val('');
		$("#ClienGestContacto" ).val('');
		$("#ClienGestStatus" ).val('');
		$("#ClienGestProducto" ).val('');
		$("#ClienGestObservac" ).val('');
		$("#ClienGestObservac2" ).val('');
		$("#ClienGestCedulaorif" ).val($( ".table_info" ).find( "tr.seleccionado").attr('name'));
	}
});

$('#button_gestion').click(function(){
	id_gestion = $("#ClienGestEditar" ).val();
	cedula = $("#ClienGestCedulaorif" ).val();
	fecha_reg = $("#ClienGestFecha" ).val();
	proximag = $("#ClienGestProximaGestion" ).val();
	telefono = $("#ClienGestTelefono").val();
	contacto = $("#ClienGestContacto").val();
	status = $("#ClienGestStatus").val();
	producto = $("#ClienGestProducto" ).val();
	comentario2 = $("#ClienGestObservac2" ).val();
	nombre = $("#ClienGestNombre" ).val();
	apellido = $("#ClienGestApellido" ).val();
	parentesco = $("#ClienGestParentesco" ).val();
	fecha_pago = $("#ClienGestFechaPago" ).val();
	bolivares = $("#ClienGestBolivares" ).val();
	gestor = $( "#table_info_deudores" ).find( "tr.seleccionado td.gestor_seleccionado").attr('name');
	if (comentario2 != '' && proximag != '0000-00-00' && fecha_reg != '0000-00-00' && status != '' && producto != '' && telefono != '' && contacto != '') {
		if ((status != 'PP' && status != 'MM') || (status == 'PP' && bolivares != '') || (status == 'MM' && nombre != '' && apellido != '' && parentesco != '')) {
			$.ajax({
				type:'POST',
				dataType:'JSON',
				data:{id_gestion:id_gestion,fecha_reg:fecha_reg,proximag:proximag,telefono:telefono,contacto:contacto,status:status,producto:producto,comentario2:comentario2,cedula:cedula,nombre:nombre,apellido:apellido,parentesco:parentesco,fecha_pago:fecha_pago,bolivares:bolivares,gestor:gestor},
				url:"<?php echo Router::url(array('action'=>'editar_gestion')); ?>",
				success:function(data){
					if (id_gestion != 0) {
						//Actualizo los valores que cambie
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_fecha_reg").html(fecha_reg);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_fecha_reg").attr('name',fecha_reg);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_telefono").html(telefono);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_telefono").attr('name',telefono);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_contacto").html(contacto);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_contacto").attr('name',contacto);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_cond_deud").html(status);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_cond_deud").attr('name',status);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_producto").html(producto);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_producto").attr('name',producto);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_proximag").html(proximag);
						$( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_proximag").attr('name',proximag);
						$(".comentario_seleccionado #input_comentario" ).val(data.comentario);
						$(".comentario_seleccionado #input_comentario2" ).val(comentario2);
					} else {
						//Si estoy agregando tengo que agregar el tr
						supervisor = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_supervisor").attr('name');
						info_gestiones = '<tr class="inner_gestiones" name = "'+data.id+'">';
						info_gestiones += '<td>'+data.numero+'</td>';
						info_gestiones += '<td class="gestion_fecha_reg" name = "'+fecha_reg+'">'+fecha_reg+'</td>';
						info_gestiones += '<td class="gestion_telefono" name = "'+telefono+'">'+telefono+'</td>';
						info_gestiones += '<td class="gestion_producto" name = "'+producto+'">'+producto+'</td>';
						info_gestiones += '<td class="gestion_cond_deud" name = "'+status+'">'+status+'</td>';
						info_gestiones += '<td class="gestion_proximag" name = "'+proximag+'">'+proximag+'</td>';
						info_gestiones += '<td class="gestion_contacto" name = "'+contacto+'">'+contacto+'</td>';
						info_gestiones += '<td>'+data.gestor+'</td>';
						info_gestiones += '<td>Mot_dev cableado</td>';
						info_gestiones += '<td class="gestion_supervisor" name ="'+supervisor+'">'+supervisor+'</td>';
						info_gestiones += '</tr>';
						$(info_gestiones).insertAfter("#"+data.rif+" .tabla_gestiones table .encabezado");
						//$("#"+data.rif+" .tabla_gestiones table").prepend(info_gestiones);
					}
					$('#editar_gestion').dialog("close");
				},error: function() {
					alert("error_editar");
				}
			});
		} else{
			alert('Debe llenar todos los campos');
			return false
		}
	} else{
		alert('Debe llenar todos los campos');
		return false
	}
});

$('.pickDate_diasLaborables').datepicker({
	dateFormat: "yy-mm-dd",
	// beforeShowDay: $.datepicker.noWeekends,
	// minDate: new Date(2012, 10 - 1, 25)
	beforeShowDay: disableSpecificDaysAndWeekends
});

$('.pickDate_diasLaborables_mayorHoy').datepicker({
		dateFormat: "yy-mm-dd",
		// beforeShowDay: $.datepicker.noWeekends,
		// minDate: new Date(2012, 10 - 1, 25)
		beforeShowDay: disableSpecificDaysAndWeekends2
	});

function disableSpecificDaysAndWeekends(date) {
	var disabledSpecificDays = [];
	 
	<?php foreach ($dias_no_laborables as $f){ ?>
			disabledSpecificDays.push(<?php echo json_encode($f['Dia']['fecha'])?>);
		<?php }
	?>

	// console.debug(disabledSpecificDays);
    // var m = date.getMonth();
	var m = ("0" + (date.getMonth() + 1)).slice(-2)
    // var d = date.getDate();
	var d = ("0" + date.getDate()).slice(-2);
    var y = date.getFullYear();
	
	var fecha = y + '-' + (m) + '-' + d;	
    for (var i = 0; i < disabledSpecificDays.length; i++) {
        if ($.inArray(fecha, disabledSpecificDays) != -1) {
            return [false];
        }
    }

    var noWeekend = $.datepicker.noWeekends(date);
    return !noWeekend[0] ? noWeekend : [true];
}

function disableSpecificDaysAndWeekends2(date) {
	var disabledSpecificDays = [];
	 
	<?php foreach ($dias_no_laborables as $f){ ?>
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

$("#ClienGestProducto").change(function(){
	producto_seleccionado = $(this).val();
	listar_status(producto_seleccionado);
});

function listar_status(producto) {
	$.ajax({
		type:'POST',
		dataType:'JSON',
		data:{producto:producto},
		url:"<?php echo Router::url(array('action'=>'buscar_status')); ?>",
		success:function(data){
			options ="<option value=''></option>";
			statu_seleccionado = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_cond_deud").attr('name');
			$.each(data,function(i,v){
				if (v.Statu.codigo == statu_seleccionado && boton == 'editar') {
					options +="<option selected value='"+v.Statu.codigo+"'>"+v.Statu.condicion+"</option>";
				} else {
					options +="<option value='"+v.Statu.codigo+"'>"+v.Statu.condicion+"</option>";
				}
			});
			$("#ClienGestStatus").html(options);
		},error: function() {
			alert("error_listar_status");
		}
	});
}

$("#ClienGestStatus").change(function(){
	status = $(this).val();
	producto = $("#ClienGestProducto").val();
	$.ajax({
		type:'POST',
		dataType:'JSON',
		data:{status:status,producto:producto},
		url:"<?php echo Router::url(array('action'=>'buscar_comentario')); ?>",
		success:function(data){
			$("#ClienGestObservac").val(data);
			if (status == 'PP' || status == 'MM') {
				$("#ClienGestObservac").width("95px");
				if (status == 'PP') {
					$("#mensaje_MM").hide();
					$("#mensaje_PP").show();
				} else {
					$("#mensaje_PP").hide();
					$("#mensaje_MM").show();
				}
			} else {
				$("#mensaje_MM").hide();
				$("#mensaje_PP").hide();
				$("#ClienGestObservac").width('auto');
			}
		},error: function() {
			alert("error_comentario");
		}
	});
});

function listar_productos_por_deudor(cedula_deudor,boton) {
	$.ajax({
		type:'POST',
		dataType:'JSON',
		data:{cedula_deudor:cedula_deudor},
		url:"<?php echo Router::url(array('action'=>'buscar_productos_por_deudor')); ?>",
		success:function(data){
			options ="<option value=''></option>";
			producto_seleccionado = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_producto").attr('name');
			$.each(data,function(i,v){
				if (i == producto_seleccionado && boton == 'editar') {
					options +="<option selected value='"+i+"'>"+v+"</option>";
				} else {
					options +="<option value='"+i+"'>"+v+"</option>";
				}
			});
			$("#ClienGestProducto").html(options);
		},error: function() {
			alert("error_listar_productos");
		}
	});
}

function listar_telefonos_deudor(cedula_deudor) {
	$.ajax({
		type:'POST',
		dataType:'JSON',
		data:{cedula_deudor:cedula_deudor},
		url:"<?php echo Router::url(array('action'=>'listar_telefonos_deudor')); ?>",
		success:function(data){
			options ="<option value=''></option>";
			telefono_seleccionado = $( ".tabla_gestiones" ).find( "tr.seleccionado td.gestion_telefono").attr('name');
			$.each(data,function(i,v){
				if (i == telefono_seleccionado && boton == 'editar') {
					options +="<option selected value='"+i+"'>"+v+"</option>";
				} else {
					options +="<option value='"+i+"'>"+v+"</option>";
				}
			});
			$("#ClienGestTelefono").html(options);
		},error: function() {
			alert("error_listar_telefonos");
		}
	});
}
</script>