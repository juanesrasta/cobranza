<?php
	// debug($consultas);
 if(!isset($empresa)){
	$empresa = 0;
}
?>

<div class="page-header" style = "text-align: center;">
	<h1> Generar Archivo <br>  <small></small></h1>
</div>


<div class = "search_box" style= "margin: 1%;">
	<fieldset class = "gest_status_wrapper">
		<fieldset class = 'gest_status'>
			<?php
				// debug($deudores);
			
				echo $this->Form->create(null, array(
			'url' => array_merge(array('controller' => 'gestion', 'action' => 'admin_generar_archivo'), $this->params['pass'])
		));
				echo $this->Form->input('empresa', array('label' => 'Empresa ', 'empty' => 'Todas','id' => 'empresa_select'));				
				
			?>
		</fieldset>
		<div style = "float: left;">
			<fieldset style = "width: 300px;">
				<legend> Cédula / RIF </legend>
				<table>
					<tr>
						<td style = "width: 100px;">
							<?php
								echo 'Todas '.  $this->Form->checkbox('todas', array('hiddenField' => false, 'id' => 'todas_check'));
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
						echo $this->Form->input('cedula', array('label' => false, 'empty' => 'Todos','id' => 'cedulaorif_input','type' => 'text','readonly' => 'false', 'class' => 'short_input', 'style' => 'display: none;'));
					?>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
		<div style = "margin-left: 10px; float: left;">
			<table style = "width : 200px;">
				<tr>
					<td style = "text-align: center;">
						<?php echo "Fecha: " . date("j/ n/ Y"); ?>
					</td>
				</tr>
				<tr>
					<td style = "text-align: center;">Desde:</td>
				</tr>
				<tr>
					<td style = "text-align: center;">
						<?php
							echo $this->Form->input('fecha_desde', array('label' => false, 'empty' => 'Todos','id' => 'desde_date','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
						?>
					</td>
				</tr>
				<tr>
					<td style = "text-align: center;">Hasta:</td>
				</tr>
				<tr>
					<td style = "text-align: center;">
						<?php
							echo $this->Form->input('fecha_hasta', array('label' => false, 'empty' => 'Todos','id' => 'hasta_date','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
						?>
					</td>
				</tr>
				<tr>
					<td style = "text-align: center;"><?php
							echo $this->Form->submit(__('Consultar'));
					?></td>
				</tr>
			</table>
		</div>
		
		<!-- checkboxes para los filtros de los archivos -->
		
		<div style = "margin-left: 10px; float: right;">
			<table style = "width : 130px;">
				<tr>
					<td style = "text-align: center;">
						<?php echo "Sumar cuentas sin gestión vigente". $this->Form->checkbox('sin_gestionv', array('hiddenField' => false,'id' => 'sin_gestionv')); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php
							echo " Sumar cuentas sin gestión". $this->Form->checkbox('sin_gestion', array('hiddenField' => false,'id' => 'sin_gestion'));
						?>
					</td>
				</tr>
			</table>
		</div>
		
		<!--  fin ultima tabla -->
		
		<div class = "generate_buttons" style = "clear: both; float: right;">
			<?php 
				if($empresa == '7'){
					echo $this->Form->input('Generar TXT', array('id' => 'txt_button','type' => 'button', 'label' => false, 'onclick' => 'generate_txt_vzla()'));
					echo $this->Form->input('Generar Excel', array('id' => 'excel_button', 'type' => 'button', 'label' => false, 'onclick' => 'generate_excel_vzla()'));
				}
				
				if($empresa == '11'){
					echo $this->Form->input('Generar TXT', array('id' => 'txt_button','type' => 'button', 'label' => false, 'onclick' => 'generate_txt()'));
					echo $this->Form->input('Generar Excel', array('id' => 'excel_button', 'type' => 'button', 'label' => false, 'onclick' => 'generate_excel()'));
				}
			
			?>
		</div>

</fieldset>

</div>

<?php
	// Banco de Venezuela!
	if($empresa == '7'){

	?>

	<table class="table table-hover user_table" id = "export_table_vzla">
			<tr>
				<td style = "color: black;  font-weight: bold;">
					<!-- Nº CEDULA Esto es Banco de Vzla??  no?? -->
					GRUPO
					<!-- 0 para Tarjetas de crédito
						2 para Creditos por cuotas
					 -->
				</td>
				<td style = "color: black;  font-weight: bold;">
					<!-- Nº CLIENTE -->
					NUMERO DE CONTRATO
					<!--20 dígitos Banco de Venezuela.
						16 dígitos Banco Caracas.
					 -->
				</td>
				<td style = "color: black;  font-weight: bold;">
					<!-- NOMBRE DEL CLIENTE -->
					FECHA Y HORA DE LA GESTIÓN
					<!-- mmddyyyy hh:mm:ss -->
				</td>
				<td style = "color: black;  font-weight: bold;">
					<!-- PERSONA CONTACTADA -->
					CÓDIGO DE ACCIÓN
					<!-- LO -->
				</td>
				<td style = "color: black;  font-weight: bold;">
					<!-- TELEFONO DE CONTACTO -->
					CÓDIGO DE RESULTADO
					<!-- PP, MM, M2, C6, N4, I8, N2, P6. -->
				</td>
				<td style = "color: black;  font-weight: bold;">
					CÓDIGO DE CARTA
					<!-- dejar dos espacios en blanco -->
				</td>
				<td style = "color: black;  font-weight: bold;">
					<!-- FECHA PROMESA -->
					CÓDIGO DE LA RECUPERADORA
					<!-- G001, B001, C001 -->
				</td>
				<td style = "color: black;  font-weight: bold;">
					<!-- FECHA DE LA GESTION -->
					NÚMERO DE TELÉFONO
				</td>
				<td style = "color: black;  font-weight: bold;">
					<!-- DESCRIPCION GESTION -->
					NÚMERO DE TELÉFONO 
					<!-- 10 DÍGITOS -->
				</td>
			</tr>
	<?php
		// debug($empresas);
		foreach($consultas as $consulta){ ?>
			
			<tr>
				<td>
					<?php 
						// echo $consulta['ClienGest']['rif_emp'];
						// echo $consulta['ClienGest']['cedulaorif'];
						if(isset($consulta['ClienGest']['producto'])){
							if($consulta['ClienGest']['producto'] == 'TDC'){
								echo '0';
							}else{
								echo '2';
							}
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
						if(isset($consulta['ClienGest']['cuenta'])){
							echo $consulta['ClienGest']['cuenta'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
						// no estoy seguro que esta fecha sea la fecha y hora de la gestión
						if(isset($consulta['ClienGest']['fecha'])){
							echo $consulta['ClienGest']['fecha'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
						// echo $consulta['ClienGest']['pers_cont'];
						// echo $consulta['ClienGest']['producto'];		
						// Codigo de accion ¿?¿?¿? ni idea que es esto
						// echo $consulta['ClienGest']['producto'];
					?>
				</td>
				<td>
					<?php
						// echo $consulta['ClienGest']['cuenta'];
						// echo $consulta['ClienGest']['telefono'];
						if(isset($consulta['ClienGest']['cond_deud'])){
							echo $consulta['ClienGest']['cond_deud'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
						// echo $consulta['ClienGest']['fecha'];
						// codigo de carta? espacio en blanco?? ¿?¿?¿?
						
					?>
				</td>
				<td>
					<?php
						
						// codigo de la recuperadora?  ¿?¿?¿?
						// echo $consulta['ClienGest']['fecha'];
					?>
				</td>
				<td>
					<?php
						// telefono 1
						if(isset($consulta['ClienGest']['telefono'])){
							echo $consulta['ClienGest']['telefono'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
					// telefono 2?? ¿?¿?
						if(isset($consulta['ClienGest']['telefono'])){
							echo $consulta['ClienGest']['telefono'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
			</tr>
			
	<?php
		}
	?>

	</table>
	
<?php }
		// Banco Bicentenario
	if(($empresa == '11' || $empresa == 0)){ ?>



	<table class="table table-hover user_table" id = "export_table">
			<tr>
				<td style = "color: black;  font-weight: bold;">
					Nº CEDULA
				</td>
				<td style = "color: black;  font-weight: bold;">
					Nº CLIENTE
				</td>
				<td style = "color: black;  font-weight: bold;">
					NOMBRE DEL CLIENTE
				</td>
				<td style = "color: black;  font-weight: bold;">
					PERSONA CONTACTADA
				</td>
				<td style = "color: black;  font-weight: bold;">
					TELEFONO DE CONTACTO
				</td>
				<td style = "color: black;  font-weight: bold;">
					TIPO DE GESTION
				</td>
				<td style = "color: black;  font-weight: bold;">
					FECHA PROMESA
				</td>
				<td style = "color: black;  font-weight: bold;">
					FECHA DE LA GESTION
				</td>
				<td style = "color: black;  font-weight: bold;">
					DESCRIPCION GESTION
				</td>
			</tr>
	<?php
		// debug($consultas);
		foreach($consultas as $consulta){ ?>
			
			<tr>
				<td>
					<?php 
						// echo $consulta['ClienGest']['rif_emp'];
						if(isset($consulta['ClienGest']['cedulaorif'])){
							echo $consulta['ClienGest']['cedulaorif'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
						if(isset($consulta['ClienGest']['numero'])){
							echo $consulta['ClienGest']['numero'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
						if(isset($consulta['Cobranza']['NOMBRE'])){
							echo $consulta['Cobranza']['NOMBRE'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
						if(isset($consulta['ClienGest']['pers_cont'])){
							echo $consulta['ClienGest']['pers_cont'];
						}else{
							echo 'sin gestión';
						}
						// echo $consulta['ClienGest']['producto'];		
					?>
				</td>
				<td>
					<?php
						if(isset($consulta['ClienGest']['telefono'])){
							echo $consulta['ClienGest']['telefono'];
						}else{
							echo 'sin gestión';
						}
						// echo $consulta['ClienGest']['cuenta'];				
					?>
				</td>
				<td>
					<?php
						// echo $consulta['ClienGest']['fecha'];
						// tipo de gestion!
					?>
				</td>
				<td>
					<?php						
						// no estoy seguro que este sea el campo
						if(isset($consulta['ClienGest']['fecha'])){
							echo $consulta['ClienGest']['fecha'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
						// tampoco estoy seguro de esta fecha!
						if(isset($consulta['ClienGest']['fecha_reg'])){
							echo $consulta['ClienGest']['fecha_reg'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>
				<td>
					<?php
						if(isset($consulta['ClienGest']['observac'])){
							echo $consulta['ClienGest']['observac'];
						}else{
							echo 'sin gestión';
						}
					?>
				</td>	
			</tr>
			
	<?php
		}
	?>

	</table>
	
	
	
<?php 
	}
?>

<script>
$('#pickDate1').datepicker({
    dateFormat: "dd-mm-yy",
});
$('#pickDate2').datepicker({
    dateFormat: "dd-mm-yy",
});
$('#pickDate3').datepicker({
    dateFormat: "dd-mm-yy",
});
$('#desde_date').datepicker({
    dateFormat: "dd-mm-yy",
});
$('#hasta_date').datepicker({
    dateFormat: "dd-mm-yy",
});

$('#cedulaorif').change(function(){
	if(this.checked){
		$('#todas_check').attr('checked', false);
		$('#cedulaorif_input').show();
	}else{
		$('#cedulaorif_input').hide();
	}
});

$('#todas_check').change(function(){
	if(this.checked){
		$('#cedulaorif').attr('checked', false);
		$('#cedulaorif_input').hide();
	}else{
		$('#todas_check').attr('checked', true);
		$('#cedulaorif_input').hide();
	}
});

function generate_txt(){
	$('#export_table').tableExport({type:'txt',escape:'false'});
}

function generate_excel(){
	$('#export_table').tableExport({type:'excel',escape:'false'});
}

function generate_txt_vzla(){
	console.debug('txt_vzla');
	$('#export_table_vzla').tableExport({type:'txt',escape:'false'});
}

function generate_excel_vzla(){
	console.debug('excel_vzla');
	$('#export_table_vzla').tableExport({type:'excel',escape:'false'});
}
	
</script>