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
				echo $this->Form->input('gestor_id', array(
					'label' => 'Gestor ',
					 'empty' => 'Todos',
					 'id' => 'gestor_select'));
                 echo $this->Form->input('mes_id', array(
                    'label' => 'Mes ',
                    'empty' => 'Todos',
                     'options' => $meses,
                    'id' => 'mes_select'));
			?>
		</fieldset>
		<div style = "clear: left;">
            <?php
            echo $this->Form->radio('Pagos',array('ambos'=> 'Ambos','confirmado'=> 'Confirmados','pendiente' => 'Pendiente'));
            ?>
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
if (!empty($arreglo_comisiones)) { ?>
    <table class="table table-hover user_table" id = "export_table">
        <tr>
            <th>CedulaOrif</th>
            <th>Producto</th>
            <th>Cuenta</th>
            <th>Dias Mora</th>
            <th>Fech_Pago</th>
            <th>Total_Pago</th>
            <th>Comision</th>
            <th>PagoGestor</th>
            <th>clave</th>
        </tr>
        <?php
        foreach ($arreglo_comisiones as $c){ ?>
            <tr>
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
        }
        ?>

    </table>
<?php
}?>

<script>
function generate_excel(){
    $('#table_clienPagos').tableExport({type:'excel',escape:'false'});
}
function generate_excel(){
	$('#export_table').tableExport({type:'excel',escape:'false'});
}
	
</script>