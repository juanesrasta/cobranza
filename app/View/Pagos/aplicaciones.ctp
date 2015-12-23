<div class = "search_box" style= "margin: 1%; ">
	<fieldset class = "gest_status_wrapper">	
		<div class="aplicaciones_busqueda_general" style="">	

		<div style = "font-weight:bold; font-size: 16px; ">Empresa: Banco Venezuela</div>
			
		<fieldset>
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

		<div style = "clear: left;">
			<fieldset >
				<legend> Mostrar por Fecha </legend>
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
			<fieldset >

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
				Unique_id
			</th>
			<th>
				RIF_Emp
			</th>
			<th>
				Empresa
			</th>
			<th>
				Cedula o RIF
			</th>
			<th>
				Nombre
			</th>
			<th>
				Producto
			</th>
			<th>
				Cuenta
			</th>
			<th>
				Fecha_Registro
			</th>
			<th>
				Fecha_Pago
			</th>
			<th>
				Pago
			</th>

		</tr>
        <?php
        $confirmados = 0;
        $pendientes = 0;
		foreach($pagos as $p) {?>
            <tr class = "tr_comisiones">
            	<td><?php echo $p['ClienPago']['unique_id']?></td>
                <td><?php echo $p['ClienPago']['RIF_EMP']?></td>
                <td><?php echo $p['Cliente']['nombre']?></td>
                <td><?php echo $p['ClienPago']['CEDULAORIF']?></td>
                <td><?php echo $p['Data']['Nombre']?></td>
                <td><?php echo $p['ClienPago']['PRODUCTO']?></td>
                <td><?php echo $p['ClienPago']['CUENTA']?></td>
                <td><?php echo $p['ClienPago']['FECH_REG']?></td>
                <td><?php echo $p['ClienPago']['FECH_PAGO']?></td>
                <td><?php echo $p['ClienPago']['TOTAL_PAGO']?></td>
            </tr>
        <?php
        	if($p['ClienPago']['EST_PAGO'] == 'confirmado') $confirmados++;
        	if($p['ClienPago']['EST_PAGO'] == 'pendiente') $pendientes++;
           	
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
	function generate_txt(){
	$('#export_table').tableExport({type:'txt',escape:'false'});
}

function generate_excel(){
	$('#export_table').tableExport({type:'excel',escape:'false'});
}
</script>