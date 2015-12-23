<div class="page-header" style = "text-align: center;">
	<h1> Pagos y comisión por gestor <br>  <small></small></h1>
</div>


<div class = "search_box" style= "margin: 1%;">
	<fieldset class = "gest_status_wrapper">
		<fieldset class = 'comision_busqueda_general'>
			<?php
				//echo $this->Form->create(null);
				echo $this->Form->input('supervisor_id', array(
					'label' => 'Supervisor ', 
					'empty' => 'Todos',
					'id' => 'supervisor_select'));
				echo $this->Form->input('gestor_id', array(
					'label' => 'Gestor ',
					 'empty' => 'Todos',
					 'id' => 'select_gestor'));
				echo $this->Form->input('client_id', array(
					'label' => 'Empresa ',
					'empty' => 'Todas',
					'id' => 'empresa_select'));		
			?>
		</fieldset>
		<div style = "margin-left: 10px; float: right;">
			<?php
			echo $this->Form->input('mes_id', array('label' => 'Mes ','id' => 'mes_select','value'=>$mes,'options'=>$meses));
			?>
		</div>
		<div style = "clear: left;">
			<fieldset style = "width: 300px;">
				<legend> Cédula / RIF </legend>
				<table>
					<tr>
						<td style = "width: 100px;">
							<?php
								echo 'Todas '.  $this->Form->checkbox('todas', array('hiddenField' => false, 'id' => 'todas_check','checked'=>true));
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
							<?php echo "Específica ". $this->Form->checkbox('cedulaorif', array('hiddenField' => false,'id' => 'cedulaorif')); ?>
						</td>
						<td style = "text-align: center;">
							<?php 
						echo $this->Form->input('cedula', array('label' => false, 'empty' => 'Todos','id' => 'cedulaorif_input','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
					    ?>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		
		<div class = "generate_buttons" style = "clear: both; float: right;">
			<?php 
				echo $this->Form->input('Generar Excel', array('id' => 'excel_button', 'type' => 'button', 'label' => false, 'onclick' => 'generate_excel()'));
			
			?>
		</div>

</fieldset>

</div>

<table class="table table-hover user_table" id = "table_clienPagos">
		<tr>
			<th>
				Cedula o Rif
			</th>
			<th>
				Producto
			</th>
			<th>
				Cuenta
			</th>
			<th>
				Dias mora
			</th>
			<th>
				Fecha_Pago
			</th>
			<th>
				Total_Pago
			</th>
			<th>
				Comision
			</th>
			<th>
				Pago Gestor
			</th>
			<th>
				Clave
			</th>
		</tr>
        <?php
        $comision_total = 0;
        $pago_total = 0;
		foreach($arreglo_comisiones as $c) {?>
            <tr class = "tr_comisiones">
                <td><?php echo $c['ClienPago']['CEDULAORIF']?></td>
                <td><?php echo $c['ClienPago']['PRODUCTO']?></td>
                <td><?php echo $c['ClienPago']['CUENTA']?></td>
                <td><?php echo $c['ClienPago']['diasMora']?></td>
                <td><?php echo $c['ClienPago']['FECH_PAGO']?></td>
                <td><?php echo $c['ClienPago']['TOTAL_PAGO']?></td>
                <td><?php echo $c['ClienPago']['comision']?></td>
                <td><?php echo $c['ClienPago']['pagoGestor']?></td>
                <td><?php echo $c['Gestor']['Clave']?></td>
            </tr>
        <?php
            $comision_total = $comision_total + $c['ClienPago']['pagoGestor'];
            $pago_total = $pago_total + $c['ClienPago']['TOTAL_PAGO'];
        }
        ?>
</table>
<div class="totales" style="float:right">
    <table>
        <tr>
            <td>Cantidad: <?php echo $registros?></td>
            <td>Total Bs: <?php echo $pago_total?></td>
            <td>Comision: <?php echo $comision_total?></td>
        </tr>
    </table>
</div>
<script>

$(".search_box").change(function(){
	buscar_comisiones();
});

$("#mes_select").change(function(){
    buscar_comisiones();
});

$('#todas_check').change(function(){
    if($('#todas_check').is(':checked')) {
        $('#cedulaorif').attr('checked', false);
        $('#cedulaorif_input').val('');
        $('#cedulaorif_input').attr('disabled', true);
    }
});
$('#cedulaorif').change(function(){
    if($('#cedulaorif').is(':checked')) {
        $('#todas_check').attr('checked', false);
        $('#cedulaorif_input').attr('disabled', false);
    }
});

function generate_excel(){
    if ($('#table_clienPagos td').is(':empty')){
        $('#table_clienPagos').tableExport({type:'excel',escape:'false'});
    } else {
        alert('No es posible generar un excel sin registros');
    }
}

function buscar_comisiones() {
    if($('#cedulaorif').is(':checked')) {
        check_especifica = 1;
    } else {
        check_especifica = 0;
    }
	$.ajax({
			type:'POST',
			dataType:'JSON',
			data:{check_especifica:check_especifica,cedula:$("#cedulaorif_input").val(),supervisor_id:$("#supervisor_select").val(),gestor_id:$("#select_gestor").val(),cliente_id:$("#empresa_select").val(),mes:$("#mes_select").val()},
			url:"<?php echo Router::url(array('action'=>'buscar_comision')); ?>",
			success:function(data){
               // debug(data);
				info_comisiones ='';
                $('.tr_comisiones').remove();
                registros = 0;
                pago_total = 0;
                comision_total = 0;
				$.each(data,function(i,v){ // La data es un arreglo igual al que viene en principio sin usar las busquedas
					info_comisiones += '<tr class="tr_comisiones">';
					info_comisiones += '<td>'+v.ClienPago.CEDULAORIF+'</td>';
					info_comisiones += '<td>'+v.GruposProducto.Nombre+'</td>';
					info_comisiones += '<td>'+v.ClienPago.CUENTA+'</td>';
					info_comisiones += '<td>'+v.ClienPago.diasMora+'</td>';
					info_comisiones += '<td>'+v.ClienPago.FECH_PAGO+'</td>';
					info_comisiones += '<td>'+v.ClienPago.TOTAL_PAGO+'</td>';
					info_comisiones += '<td>'+v.ClienPago.comision+'</td>';
					info_comisiones += '<td>'+v.ClienPago.pagoGestor+'</td>';
					info_comisiones += '<td>'+v.Gestor.Clave+'</td>';
					info_comisiones += '</tr>';
					registros++;
                    pago_total = pago_total+v.ClienPago.TOTAL_PAGO;
                    comision_total = comision_total +v.ClienPago.pagoGestor;

				});
				$('#table_clienPagos').append(info_comisiones);
				$('.totales').html('<table><tr> <td>Cantidad:'+registros+'</td><td>Total Bs: '+pago_total+'</td><td>Comision: '+comision_total+'</td></tr></table>');
			},
		});

}

$("#supervisor_select").change(function(){
    if(!isNaN($(this).val())){ //Carga los gestores
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{supervisor_id:$("#supervisor_select").val()},
            url:"<?php echo Router::url(array('controller' => 'gestion','action'=>'actualizar_gestores')); ?>",
            success:function(data){
                options_p = "<option value = ''>TODOS</option>";
                $.each(data.gestores,function(i,v){
                    options_p +="<option value='"+i+"'>"+v+"</option>";
                });
                $("#select_gestor").html(options_p);
            },
            beforeSend:function(){
                $("#supervisor_select").attr('disabled',true);
            },
            complete:function(){
                $("#supervisor_select").removeAttr('disabled');
                //buscar_deudores();
            }

        });
    }
});

</script>