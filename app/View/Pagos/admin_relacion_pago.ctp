<div class="page-header" style = "text-align: center;">
	<h1> Relacion de Pagos <br>  <small></small></h1>
</div>
<div class = "search_box" style= "margin: 1%;">
	<fieldset class = "gest_status_wrapper">
		<fieldset class = 'comision_busqueda_general'>
			<?php
				echo $this->Form->create(null);
				echo $this->Form->input('supervisor_id', array('label' => 'Supervisor ', 'empty' => 'Todos','id' => 'supervisor_select'));
				echo $this->Form->input('gestor_id', array('label' => 'Gestor ', 'empty' => 'Todas','id' => 'select_gestor'));
				echo $this->Form->input('cliente_id', array('label' => 'Empresa ', 'empty' => 'Todas','id' => 'empresa_select'));
			?>
		</fieldset>
		<div style = "margin-left: 10px; float: right;">

		</div>
		<div style = "clear: left;" class="comision_busqueda_mes">
            <fieldset style = "width: 300px;">
                <legend> Mostrar por fecha </legend>
                <?php
                echo $this->Form->input('check_por_mes', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'id' => 'mostrar_mes',
                    'style'=> 'float: left;',
                    'class' => 'radio_button'
                ));
                echo $this->Form->input('mes', array('label' => 'Mes del pago ', 'empty' => 'Todos', 'options' => array(
                    '01' => 'Enero',
                    '02' => 'Febrero',
                    '03' => 'Marzo',
                    '04' => 'Abril',
                    '05' => 'Mayo',
                    '06' => 'Junio',
                    '07' => 'Julio',
                    '08' => 'Agosto',
                    '09' => 'Septiembre',
                    '10' => 'Octubre',
                    '11' => 'Noviembre',
                    '12' => 'Diciembre',
                )));

                echo $this->Form->input('check_por_fecha', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'style'=> 'float: left;clear:left',
                    'class' => 'radio_button',
                    'id' => 'mostrar_fecha'
                ));
                echo $this->Form->input('dia_pago', array('label' => 'Dia del pago','id' => 'fecha_pago'));

                echo $this->Form->input('confirmado', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'style'=> 'float: left;',
                    'class' => 'radio_button'
                ));

                echo '<span style="margin: 0px 5px 0px 9px;"> Confirmado </span>';

                ?>
            </fieldset>
        </div>
        <?php echo $this->Form->submit('Buscar'); ?>
		<div class = "generate_buttons" style = "float: right;">
			<?php 
				echo $this->Form->input('Exportar a Excel', array('id' => 'excel_button', 'type' => 'button', 'label' => false, 'onclick' => 'generate_excel()'));
			?>
		</div>

</fieldset>

</div>

<table class="table table-hover user_table" id = "export_table">
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
                Status
            </th>
            <th>
                Fecha_Confirmacion
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
				Gestor
			</th>
		</tr>
<?php
    foreach ($pagos as $p){ ?>
        <tr>
            <td><?php echo $p['ClienPago']['CEDULAORIF']?></td>
            <td><?php echo $p['ClienPago']['PRODUCTO']?></td>
            <td><?php echo $p['ClienPago']['CUENTA']?></td>
            <td><?php echo $p['ClienPago']['diasMora']?></td>
            <td><?php echo $p['ClienPago']['FECH_PAGO']?></td>
            <td><?php echo $p['ClienPago']['EST_PAGO']?></td>
            <td><?php echo $p['ClienPago']['FECHA_CONF']?></td>
            <td><?php echo $p['ClienPago']['TOTAL_PAGO']?></td>
            <td><?php echo $p['ClienPago']['comision']?></td>
            <td><?php echo $p['ClienPago']['pagoGestor']?></td>
            <td><?php echo $p['ClienPago']['LOGIN_REG']?></td>
        </tr>
      <?php
    }
?>

</table>
<script>
function generate_excel(){
    if ($('#export_table td').is(':empty')){
        $('#export_table').tableExport({type:'excel',escape:'false'});
    } else {
        alert('No es posible generar un excel sin registros');
    }
}

$('#fecha_pago').datepicker({});

$('#mostrar_mes').change(function(){

	if($('#mostrar_mes').is(':checked')) {
        $('#UserMes').attr('disabled', false);
        $('#mostrar_fecha').attr('checked', false);
        $('#fecha_pago').attr('disabled', true);
	}
});
$('#mostrar_fecha').change(function(){
	if($('#mostrar_fecha').is(':checked')) {
        $('#mostrar_mes').attr('checked', false);
        $('#fecha_pago').attr('disabled', false);
        $('#UserMes').attr('disabled', true);
	}
});

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