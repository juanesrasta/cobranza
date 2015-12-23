<div class="page-header" style = "text-align: center;">
	<h1> Quitar confirmación de Pago <br>  <small></small></h1>
</div>
<div class = "search_box" style= "margin: 1%;">
	<fieldset class = "gest_status_wrapper">
		<fieldset class = 'comision_busqueda_general'>
			<?php
				echo $this->Form->create(null);
				echo $this->Form->input('gestor_id', array('label' => 'Gestor ', 'empty' => 'Todas','id' => 'select_gestor'));
				echo $this->Form->input('cliente_id', array('label' => 'Empresa ', 'empty' => 'Todas','id' => 'empresa_select'));
			?>
		</fieldset>
		<div style = "margin-left: 10px; float: right;">

		</div>
		<div style = "clear: left;" class="comision_busqueda_mes">
            <fieldset style = "width: 300px;">
                <?php
                echo $this->Form->input('check_por_mes', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'id' => 'mostrar_mes',
                    'style'=> 'float: left;',
                    'class' => 'radio_button',
                ));
                echo $this->Form->input('mes', array('label' => 'Mes del pago ', 'empty' => 'Todos', 'options' => $meses, 'value' => $mes_actual));

                echo $this->Form->input('check_por_fecha', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'style'=> 'float: left;clear:left',
                    'class' => 'radio_button',
                    'id' => 'mostrar_fecha'
                ));
                echo $this->Form->input('dia_pago', array('label' => 'Dia del pago','id' => 'fecha_pago'));
                ?>
            </fieldset>
        </div>
        <div style = "clear: left;" class="comision_busqueda_mes">
            <fieldset style = "width: 300px;">
                 <legend>Busqueda específica</legend>
                <?php
                echo $this->Form->input('cedula_deudor', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'id' => 'cedula_deudor',
                    'style'=> 'float: left;',
                    'class' => 'radio_button'
                ));
                echo '<span style="margin: 0px 5px 0px 9px;"> Cedula o Rif </span>';
                echo $this->Form->input('nombre_deudor', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'id' => 'nombre_deudor',
                    'style'=> 'float: left;',
                    'class' => 'radio_button'
                ));
                echo '<span style="margin: 0px 5px 0px 9px;"> Nombre </span>';
                echo $this->Form->input('cuenta', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'style'=> 'float: left;clear:left',
                    'class' => 'radio_button',
                    'id' => 'cuenta_deudor'
                ));
                echo '<span style="margin: 0px 5px 0px 9px;"> Cuenta </span>';
                echo $this->Form->input('busqueda_especifica',array(
                    'label'=>false,
                    'style' => 'float: right;margin-top: -43px;width: 147px;margin-right: 29px;height:24px'
                ));
                 ?>
            </fieldset>
        </div>
        <?php echo $this->Form->submit('Buscar',array('onClick'=> 'cambiar_boton()')); ?>
</fieldset>

</div>

<table class="table table-hover user_table" id = "export_table">
		<tr>
			<th>
				Cedula o Rif
			</th>
			<th>
				Nombre del deudor
			</th>
			<th>
				Producto
			</th>
			<th>
				Cuenta
			</th>
			<th>
				Fecha_Pago
			</th>
			<th>
				Total_Pago
			</th>
            <th>Seleccionar</th>
		</tr>
<?php
    foreach ($pagos as $p){ ?>
        <tr>
            <td><?php echo $p['ClienPago']['CEDULAORIF']?></td>
            <td><?php echo $p['Cobranza']['NOMBRE']?></td>
            <td><?php echo $p['ClienPago']['PRODUCTO']?></td>
            <td><?php echo $p['ClienPago']['CUENTA']?></td>
            <td><?php echo $p['ClienPago']['FECH_PAGO']?></td>
            <td><?php echo $p['ClienPago']['TOTAL_PAGO']?></td>
            <td><?php
                echo $this->Form->input('quita_confirmacion',array(
                    'name' => 'quitar_confirmacion['.$p['ClienPago']['unique_id'].']',
                    'type' => 'checkbox',
                    'label' => false
                ));
                ?></td>
        </tr>
      <?php
    }
?>

</table>
<?php
echo $this->Form->input('tipo_boton',array(
    'type' => 'hidden',
    'value' => 'quitar_confirmacion',
    'id' => 'tipo_boton'
));
echo $this->Form->submit('Quitar Confirmacion',array('style'=>'  float: right;margin-right: 25px;margin-bottom: 25px;')) ?>
<script>
function cambiar_boton(){
    $('#tipo_boton').val('buscar');
}

function justNumbers(e){
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
        return true;

    return /\d/.test(String.fromCharCode(keynum));
}

$('#cedula_deudor').change(function(){
	if($('#cedula_deudor').is(':checked')) {
        $('#UserBusquedaEspecifica').val('');
		$('#nombre_deudor').attr('checked', false);
		$('#cuenta_deudor').attr('checked', false);
        $('#UserBusquedaEspecifica').attr('onkeypress','return justNumbers(event)');

	}
});

$('#nombre_deudor').change(function(){
	if($('#nombre_deudor').is(':checked')) {
        $('#UserBusquedaEspecifica').val('');
		$('#cedula_deudor').attr('checked', false);
		$('#cuenta_deudor').attr('checked', false);
        $('#UserBusquedaEspecifica').removeAttr( "onkeypress" );
	}
});

$('#cuenta_deudor').change(function(){
	if($('#cuenta_deudor').is(':checked')) {
        $('#UserBusquedaEspecifica').val('');
		$('#cedula_deudor').attr('checked', false);
		$('#nombre_deudor').attr('checked', false);
        $('#UserBusquedaEspecifica').attr('onkeypress','return justNumbers(event)');
	}
});

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

$('#fecha_pago').datepicker({
});
</script>