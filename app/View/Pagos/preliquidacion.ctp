<div class = "search_box" style= "margin: 1%; ">
	<fieldset class = "gest_status_wrapper">	
		<div class="aplicaciones_busqueda_general" style="">	

		<div style = "font-weight:bold; font-size: 16px; ">Empresa: Banco Bicentenario</div>
			
		<fieldset style="float:left;margin:9px;height:125px" class="fieldset_1_preliquidacion">
			<?php 
				echo $this->Form->create("Pagos");
				echo $this->Form->input('supervisor_id', array(
					'label' => 'Supervisor ', 
					'empty' => 'Todos',
					'id' => 'supervisor_select'));
				echo $this->Form->input('gestor_id', array(
					'label' => 'Gestor ',
					 'empty' => 'Todos',
					 'id' => 'gestor_select'));	
			?>	
		</fieldset>

		<div>
			<fieldset style="float:left;margin:9px;height:125px">
				<table >
					<tr>
						
						<td style = "width: 400px;">
							<?php
								echo 'Confirmados '.  $this->Form->checkbox('confirmados', array('label'=> "Confirmados", 'hiddenField' => false, 'id' => 'confirmados_check'));
							?>
						</td>
					</tr>
					<tr>
						
						<td style = "width: 400px;" >
							<?php 
						echo $this->Form->input('fecha_id', array(
							'label' => 'Fecha de registro',
							 'empty' => 'Todos',
							 'id' => 'fecha_select'));	
					    ?>
						</td>
					</tr>


				</table>


			</fieldset>
		</div>
		<div>
			<fieldset  style="margin:9px;height:125px" class="fieldset_3_preliquidacion">

				<table style = "width: 250px;">
					<tr>
					<td>
							<?php 
						echo $this->Form->input('cedula', array('label' => 'Cedula o RIF', 'empty' => 'Todos','id' => 'cedulaorif_input','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
					    ?>
				
						</td>
					</tr>
					<tr>
						
						<td >
							<?php 
						echo $this->Form->input('nombre', array('label' => 'Nombre', 'empty' => 'Todos','id' => 'nombr_input','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
					    ?>
						</td>
					</tr>
					<tr>
						
						<td style = "text-align: left;">
							<?php 
						echo $this->Form->input('cuenta', array('label' => 'Cuenta', 'empty' => 'Todos','id' => 'cuenta_input','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
					    ?>
						</td>
						
					</tr>
				</table>	
			</fieldset>

				<div> <?php echo $this->Form->end(__('Buscar')); ?> </div>
		</div>
		<br>
		<div>		
			<div class = "generate_buttons" style = "clear: both; float: left;">
			<?php 
				echo $this->Form->input('Generar Excel', array('id' => 'excel_button', 'type' => 'button', 'label' => false, 'onclick' => 'generate_excel()'));
			
			?>
		</div>

		</div>  
	</fieldset>

	<table class="table table-hover user_table" id = "export_table">
		<tr>
			<th>
				NRO
			</th>
			<th>
				Cedula
			</th>
			<th>
				Numero Cliente
			</th>
			<th>
				Nombre Cliente
			</th>
			<th>
				Numero Credito
			</th>
			<th>
				Producto
			</th>
			<th>
				Resultado
			</th>
			<th>
				Fecha Asignacion
			</th>
			<th>
				Fecha Pago
			</th>
			<th>
				Monto Pago
			</th>
			<th>
				Monto Aplicado a Cuota
			</th>
			<th>
				Monto de la Comision	
			</th>
			<th>
				Observaciones
			</th>
		</tr>
        <?php
        $confirmados = 0;
        $pendientes = 0;
        $nro = 1;
		foreach($pagos as $p) {?>
            <tr class = "tr_comisiones">
            	<td><?php echo $nro?></td>
                <td><?php echo $p['ClienPago']['CEDULAORIF']?></td>
                <td><?php echo $p['ClienNrocliente']['NroCliente']?></td>
                <td><?php echo $p['Cobranza']['NOMBRE']?></td>
                <td><?php echo $p['ClienPago']['CUENTA']?></td>
                <td><?php echo $p['ClienPago']['PRODUCTO']?></td>
                <td><?php echo $p['Status']['condicion']?></td>
                <td><?php echo $p['Cobranza']['FECH_ASIG']?></td>
                <td><?php echo $p['ClienPago']['FECH_PAGO']?></td>
                <td><?php echo $p['ClienPago']['TOTAL_PAGO']?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php
        	if($p['ClienPago']['EST_PAGO'] == 'confirmado') $confirmados++;
        	if($p['ClienPago']['EST_PAGO'] == 'pendiente') $pendientes++;

        	$nro++;
           	
        }
        ?>
</table>

	

</div>
<div>
	Pendientes: <?php echo $pendientes; ?>

	<br>

	Confirmados: <?php echo $confirmados; ?>

</div>

<script>

	function generate_excel(){
		$('#export_table').tableExport({type:'excel',escape:'false'});
	}
</script>