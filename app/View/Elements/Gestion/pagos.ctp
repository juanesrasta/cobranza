<table style="width:67%">
	<tr>
		<td>Gestor</td>
		<td><?php echo $this->Form->input('pago_gestor',array(
			'label' => false,
			'id' => 'PagoGestor'
		))?></td>
	</tr>
	<tr>
		<td>Fecha de registro</td>
		<td><?php echo $this->Form->input('fecha_registro',array(
			'label' => false,
			'id' => 'PagoFechaRegistro',
			'value' => date('Y-m-d'),
			'class' => 'datePicker'
		))?></td>
	</tr>
	<tr>
		<td>Fecha de pago</td>
		<td><?php echo $this->Form->input('fecha_pago',array(
			'label' => false,
			'id' => 'PagoFecha',
			'class' => 'datePicker'
		))?></td>
	</tr>
	<tr>
		<td>Producto</td>
		<td><?php echo $this->Form->input('producto',array(
			'label' => false,
			'id' => 'PagoProducto',
			'type' => 'select'
		))?></td>
	</tr>
</table>
<table>
	<tr>
		<th></th>
		<th>Monto</th>
		<th>Documento</th>
	</tr>
	<tr>
		<td>Efectivo</td>
		<td><?php echo $this->Form->input('efectivo_monto',array(
			'label' => false,
			'id' => 'PagoEfectivoMonto',
			'onkeypress'=>"return soloNumerosYDecimales(event);"
		))
		?>
		<span id="error_efectivo_monto" class="error_pago" style="font-size:10px;color:red;display:none">*El monto debe ser númerico.</span>
		</td>
		<td><?php echo $this->Form->input('efectivo_documento',array(
			'label' => false,
			'id' => 'PagoEfectivoDocumento',
			'onkeypress'=>"return soloNumeros(event);",
		))?>
		<span id="error_efectivo_documento" class="error_pago" style="font-size:10px;color:red;display:none">*El documento debe ser númerico no decimal.</span>
		</td>
	</tr>
	<tr>
		<td>Cheque</td>
		<td><?php echo $this->Form->input('cheque_monto',array(
			'label' => false,
			'id' => 'PagoChequeMonto',
			'onkeypress'=>"return soloNumerosYDecimales(event);"
		))?>
		<span id="error_cheque_monto" class="error_pago" style="font-size:10px;color:red;display:none">*El monto debe ser númerico.</span>
		</td>
		<td><?php echo $this->Form->input('cheque_documento',array(
			'label' => false,
			'id' => 'PagoChequeDocumento',
			'onkeypress'=>"return soloNumeros(event);"
		))?>
		<span id="error_cheque_documento" class="error_pago" style="font-size:10px;color:red;display:none">*El documento debe ser númerico no decimal.</span>
		</td>
	</tr>
	<tr>
		<td>Otro</td>
		<td><?php echo $this->Form->input('otro_monto',array(
			'label' => false,
			'id' => 'PagoOtroMonto',
			'onkeypress'=>"return soloNumerosYDecimales(event);"
		))?>
		<span id="error_otro_monto" class="error_pago" style="font-size:10px;color:red;display:none">*El monto debe ser númerico.</span>
		</td>
		<td><?php echo $this->Form->input('otro_documento',array(
			'label' => false,
			'id' => 'PagoOtroDocumento',
			'onkeypress'=>"return soloNumeros(event);"
		))?>
		<span id="error_otro_documento" class="error_pago" style="font-size:10px;color:red;display:none">*El documento debe ser númerico no decimal.</span>
		</td>
	</tr>
	<tr>
		<td>Total Pago</td>
		<td><?php echo $this->Form->input('pago_total',array(
			'label' => false,
			'id' => 'PagoTotal'
		))?>
		<span id="error_total_pago" class="error_pago" style="font-size:10px;color:red;display:none">*El monto debe ser númerico.</span>
		</td>
		<td></td>
	</tr>
	<tr style="padding-top:30px">
		<td style="text-align:left; padding-top:30px" colspan="2"><?php echo $this->Form->input('pago_aplicar',array(
			'label' => false,
			'id' => 'PagoAplicar',
			'type' => 'checkbox',
			'style' => 'float:left'
		))?>
		No Aplicar
		</td>
		<td></td>
	</tr>
</table>
<?php echo $this->Form->button('Guardar',array('type'=>'button','id'=>'button_pago','style' => '')) ?>
<script>
	//Validar solo numeros
	function soloNumeros(e){
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8))
        return true;
         
        return /\d/.test(String.fromCharCode(keynum));
    }
	
	//Numeros y punto y coma
	function soloNumerosYDecimales(e){
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 44) || (keynum == 46))
        return true;
         
        return /\d/.test(String.fromCharCode(keynum));
    }
	
	//Hacer click en pagos
	$('.boton_pagos').click(function(){
		$('#vista_pagos').dialog();
		gestor = $( ".table_info_deudores" ).find( "tr.seleccionado td.gestor_seleccionado").attr('name');
		cedula = $( ".table_info_deudores" ).find( "tr.seleccionado").attr('name');
		$('#PagoGestor').val(gestor);
		listar_productos_por_deudor_pago(cedula);
		limpiar_pagos();
	});
	
	function limpiar_pagos() {
		$('#PagoFecha').val('');
		$('#PagoProducto').val('');
		$('#PagoEfectivoMonto').val('');
		$('#PagoEfectivoDocumento').val('');
		$('#PagoChequeMonto').val('');
		$('#PagoChequeDocumento').val('');
		$('#PagoOtroMonto').val('');
		$('#PagoOtroDocumento').val('');
		$('#PagoTotal').val('');
	}
	
	
	//Registrar el pago
	$('#button_pago').click(function(){
		efectivo_monto = $('#PagoEfectivoMonto').val();
		efectivo_documento = $('#PagoEfectivoDocumento').val();
		cheque_monto = $('#PagoChequeMonto').val();
		cheque_documento = $('#PagoChequeDocumento').val();
		otro_monto = $('#PagoOtroMonto').val();
		otro_documento = $('#PagoOtroDocumento').val();
		total_pago = $('#PagoTotal').val();
		
		//Valido que los datos sean correctos
		if (isNumeric(efectivo_monto,'decimal','error_efectivo_monto') && isNumeric(cheque_monto,'decimal','error_cheque_monto') && isNumeric(otro_monto,'decimal','error_otro_monto') && isNumeric(efectivo_documento,'entero','error_efectivo_documento') && isNumeric(cheque_documento,'entero','error_cheque_documento') && isNumeric(otro_documento,'entero','error_otro_documento') && isNumeric(total_pago,'decimal','error_total_pago')) {
			cedula = $( ".table_info_deudores" ).find( "tr.seleccionado").attr('name');
			gestor = $('#PagoGestor').val();
			fecha_reg = $("#PagoFechaRegistro" ).val();
			fecha = $("#PagoFecha" ).val();
			producto = $('#PagoProducto').val();
			$.ajax({
				type:'POST',
				dataType:'JSON',
				data:{cedula:cedula,gestor:gestor,fecha_reg:fecha_reg,fecha:fecha,efectivo_monto:efectivo_monto,efectivo_documento:efectivo_documento,cheque_monto:cheque_monto,cheque_documento:cheque_documento,otro_monto:otro_monto,otro_documento:otro_documento,total_pago:total_pago,producto:producto},
				url:"<?php echo Router::url(array('action'=>'guardar_pago')); ?>",
				success:function(data){
					info_pagos = '<tr class="inner_pagos">';
					info_pagos += '<td>'+data.ClienPago.FECH_REG+'</td>';
					info_pagos += '<td>'+data.ClienPago.FECH_PAGO+'</td>';
					info_pagos += '<td>'+data.ClienPago.TOTAL_PAGO+'</td>';
					info_pagos += '<td>'+data.ClienPago.PRODUCTO+'</td>';
					info_pagos += '<td></td>';
					info_pagos += '<td></td>';
					info_pagos += '<td>'+data.ClienPago.EFECTIVO+'</td>';
					info_pagos += '<td>'+data.ClienPago.MTO_CHEQ1+'</td>';
					info_pagos += '<td>'+data.ClienPago.MTO_OTROS+'</td>';
					info_pagos += '<td>'+data.ClienPago.Nro_Efect+'</td>';
					info_pagos += '<td>'+data.ClienPago.NRO_OTRO+'</td>';
					info_pagos += '<td></td>';
					info_pagos += '<td></td>';
					info_pagos += '</tr>';
					$(info_pagos).insertAfter("#"+data.ClienPago.RIF_EMP+" .inner_tabla_edo_cuentas .encabezado");
					$('#vista_pagos').dialog("close");
				},error: function() {
					alert("error_guardar_pagos");
				}
			});
		}
	});
	
	function listar_productos_por_deudor_pago(cedula_deudor) {
		$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{cedula_deudor:cedula_deudor},
			url:"<?php echo Router::url(array('action'=>'buscar_productos_por_deudor')); ?>",
			success:function(data){
				options ="<option value=''></option>";
				$.each(data,function(i,v){
					options +="<option value='"+i+"'>"+v+"</option>";
				});
				$("#PagoProducto").html(options);
			},error: function() {
				alert("error_listar_productos");
			}
		});
	}
	$('.datePicker').datepicker({  // calendario Jquery
		dateFormat: "dd-mm-yy",
	});
	
	function isNumeric(cadena,tipo,id){
		$('.error_pago').hide();
		if (cadena != '') {
			if (isNaN(cadena)){
				$('#'+id).show();
				return false;
			} else {
				if (tipo == 'entero') {
					if (parseFloat(cadena) % 1 == 0) {
						return true;
					} else {
						$('#'+id).show();
						return false;
					}
				}
				return true;
			}
		} else {
			return true;
		}
		return true;
	}
</script>