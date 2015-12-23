<div class="page-header" style = "text-align: center;">
	<h1> Reporte de Cartera <br>  <small></small></h1>
</div>
<div class = "search_box reporte_cartera" style= "margin: 1%;">
	<fieldset class = "gest_status_wrapper">
		<fieldset class = ''>
			<?php
				echo $this->Form->create(null);
				echo $this->Form->input('cliente_id', array(
					'label' => 'Empresa ', 
					'empty' => 'Todos',
					'id' => 'cliente_select',
                ));
				echo $this->Form->input('producto_id', array(
					'label' => 'Producto ',
					 'empty' => 'Todos',
					 'id' => 'producto_select'));
                 echo $this->Form->input('statu_id', array(
                    'label' => 'Status ',
                    'empty' => 'Todas',
                    'id' => 'status_select'));
                echo $this->Form->input('fecha_asig', array(
                    'label' => 'Fecha de Asignacion ',
                    'empty' => 'Todas',
                    'id' => 'fecha_select'));
			?>
		</fieldset>
		<div style = "margin-right:623px; float: right;">
            <?php
            echo $this->Form->radio('cartera',array('todos'=> 'Todos','asignado'=> 'Asignado','no-asignado' => 'No asignado'));
            ?>
		</div>
		<div style = "clear: left;">
			<fieldset style = "width: 300px;">
				<legend> Por gestor </legend>
                <?php
                echo $this->Form->input('gestor_id', array(
                    'label' => 'Gestor ',
                    'empty' => 'Todos',
                    'id' => 'gestor_select'));
                ?>
			</fieldset>
		</div>
        <?php echo $this->Form->submit('Consultar') ?>
		<div class = "generate_buttons" style = "clear: both; float: right;">
			<?php 
				echo $this->Form->input('Generar Excel', array('id' => 'excel_button', 'type' => 'button', 'label' => false, 'onclick' => 'generate_excel()'));
			
			?>
		</div>

</fieldset>
</div>
<?php
if (!empty($cartera)) { ?>
    <table class="table table-hover user_table" id = "export_table">
        <tr>
            <th>Empresa</th>
            <th>Cedula</th>
            <th>Nombre</th>
            <th>Fecha Asignacion</th>
            <th>Producto</th>
            <th>Cuenta</th>
            <th>Gestor</th>
            <th>Proxima Gestion</th>
            <th>Telefono</th>
            <th>Condicion</th>
            <th>observacion</th>
        </tr>
        <?php
        foreach ($cartera as $c){ ?>
            <tr>
                <?php if ($c['ClienGest']['rif_emp'] == 7) {
                    $empresa = 'BANCO DE VENEZUELA';
                } else {
                    $empresa = 'BANCO BICENTENARIO';
                }?>
                <td><?php echo $empresa ?></td>
                <td><?php echo $c['ClienGest']['cedulaorif']?></td>
                <td><?php echo $c['Cobranza']['NOMBRE']?></td>
                <td><?php echo $c['Cobranza']['FECH_ASIG']?></td>
                <td><?php echo $c['ClienGest']['producto']?></td>
                <td><?php echo $c['ClienGest']['cuenta']?></td>
                <td><?php echo $c['Cobranza']['GESTOR']?></td>
                <td><?php echo $c['ClienGest']['proximag']?></td>
                <td><?php echo $c['ClienGest']['telefono']?></td>
                <td><?php echo $c['ClienGest']['cond_deud']?></td>
                <td><?php echo $c['ClienGest']['observac']?></td>
            </tr>
        <?php
        }
        ?>

    </table>
<?php
}?>

<script>
function generate_excel(){
    $('#table_clienPagos').tableExport({type:'excel',escape:'false'});
}

$('#cliente_select').change(function(){
    actualizar_por_empresa();
})

function actualizar_por_empresa() {
	$.ajax({
        type:'POST',
        dataType:'JSON',
        data:{cliente_id:$("#cliente_select").val()},
        url:"<?php echo Router::url(array('action'=>'actualizar_por_empresa')); ?>",
        success:function(data) {
            options_p = "<option value = ''>TODOS</option>";
            if (!isEmptyJSON(data.productos)) {
                $.each(data.productos, function (i, v) {
                    options_p += "<option value='" + i + "'>" + v + "</option>";
                });
            }
            $("#producto_select").html(options_p);
            options_p = "<option value = ''>TODOS</option>";
            if (!isEmptyJSON(data.status)) {
                $.each(data.status, function (i, v) {
                    options_p += "<option value='" + i + "'>" + v + "</option>";
                });
            }
            $("#status_select").html(options_p);
        },
        beforeSend:function(){
            $("#cliente_select").attr('disabled',true);
        },
        complete:function(){
            $("#cliente_select").removeAttr('disabled');
        }
    });
}

function isEmptyJSON(obj) {
    for(var i in obj) { return false; }
    return true;
}

$('#fecha_select').datepicker({});

function generate_excel(){
	$('#export_table').tableExport({type:'excel',escape:'false'});
}
	
</script>