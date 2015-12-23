<div class="page-header" style = "text-align: center;">
	<h1>Gestiones Por Status <br>  <small></small></h1>
</div>


<div class = "search_box" style= "margin: 1%;">
	<fieldset class = "gest_status_wrapper">
		<fieldset class = 'gest_status'>
			<?php
				// debug($deudores);
			
				echo $this->Form->create(null, array(
			'url' => array_merge(array('controller' => 'gestion', 'action' => 'admin_gestiones_por_status'), $this->params['pass'])
		));
				// debug($empresa);
				echo $this->Form->input('radio', array(
					'type' => 'radio',
					'options' => array(''),
					'label' => false,
					'id' => 'empresa_radio',
					'style'=> 'float: left;',
					'class' => 'radio_button'
				));
				echo $this->Form->input('empresa', array('label' => 'Empresa ', 'empty' => 'Todas'));
				
				echo $this->Form->input('radio', array(
					'type' => 'radio',
					'options' => array(''),
					'label' => false,
					'id' => 'gestor_radio',
					'style'=> 'float: left;',
					'class' => 'radio_button'
				));
				
				echo $this->Form->input('gestore', array('label' => 'Gestor ', 'empty' => 'Todas'));
			?>
		</fieldset>
	
<?php
		
		
		echo '<table class= "gest_stat_table"> <tr><td>';	
			echo $this->Form->input('fecha1', array('label' => 'Desde:  ', 'empty' => 'Todos','id' => 'pickDate1','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
		echo '</td><td style = "text-align: right;">';
			echo 'Fecha:';
		echo '</td><td >';
			$now = new DateTime();
			echo '<b>'.  $now->format('d-m-Y').' </b>';
		echo '</td></tr><tr><td>';
			echo $this->Form->input('fecha2', array('label' => 'Hasta:  ', 'empty' => 'Todos','id' => 'pickDate2','type' => 'text','readonly' => 'false', 'class' => 'short_input'));
		echo '</td><td>';
			echo $this->Form->submit(__('Buscar'), array('style' => 'float: right;'));
		echo '</td><td>';
			// echo $this->Form->button(__('Cerrar'), array('style' => 'float: left;', 'class' => 'boton_cerrar'));
		echo '</td></tr></table>';
		
		echo $this->Form->end();
?>
</fieldset>

</div>

<table class="table table-hover user_table">
		<tr>
			<th>
				Nombre
			</th>
			<th>
				Total
			</th>
			<th>
				BD
			</th>
			<th>
				C6
			</th>
			<th>
				EX
			</th>
			<th>
				I8
			</th>
			<th>
				M2
			</th>
			<th>
				MM
			</th>
			<th>
				N2
			</th>
			<th>
				N4
			</th>
			<th>
				PP
			</th>
		</tr>
<?php
	foreach($deudores as $operador => $d){ ?>
		
		<tr>
			<td>
				<?php 
					echo $gestores[$operador]['Gestor']['Nombre'];
					// debug($d);
				?>
			</td>
			<td>
				<?php 
					echo sizeof($d);
				?>
			</td>
			<td>
				<?php
					$num = 0;
					foreach($d as $status){
						if($status['ClienGest']['cond_deud'] == 'BD'){ $num += 1;}
					}
					echo $num;
				?>
			</td>
			<td>
				<?php
					$num = 0;
					foreach($d as $status){
						if($status['ClienGest']['cond_deud'] == 'C6'){ $num += 1;}
					}
					echo $num;				
				?>
			</td>
			<td>
				<?php
					$num = 0;
					foreach($d as $status){
						if($status['ClienGest']['cond_deud'] == 'EX'){ $num += 1;}
					}
					echo $num;					
				?>
			</td>
			<td>
				<?php
					$num = 0;
					foreach($d as $status){
						if($status['ClienGest']['cond_deud'] == 'I8'){ $num += 1;}
					}
					echo $num;					
				?>
			</td>
			<td>
				<?php
					$num = 0;
					foreach($d as $status){
						if($status['ClienGest']['cond_deud'] == 'M2'){ $num += 1;}
					}
					echo $num;					
				?>
			</td>
			<td>
				<?php
					$num = 0;
					foreach($d as $status){
						if($status['ClienGest']['cond_deud'] == 'MM'){ $num += 1;}
					}
					echo $num;					
				?>
			</td>
			<td>
				<?php
					$num = 0;
					foreach($d as $status){
						if($status['ClienGest']['cond_deud'] == 'N2'){ $num += 1;}
					}
					echo $num;					
				?>
			</td>
			<td>
				<?php
					$num = 0;
					foreach($d as $status){
						if($status['ClienGest']['cond_deud'] == 'N4'){ $num += 1;}
					}
					echo $num;					
				?>
			</td>
			<td>
				<?php
					$num = 0;
					foreach($d as $status){
						if($status['ClienGest']['cond_deud'] == 'PP'){ $num += 1;}
					}
					echo $num;					
				?>
			</td>
		</tr>
		
<?php
	}
?>

</table>

<script>
$('#pickDate1').datepicker({
    dateFormat: "dd-mm-yy",
});
$('#pickDate2').datepicker({
    dateFormat: "dd-mm-yy",
});

$('.radio_button').change(function(){
	if($('#gestor_radio0').is(':checked')) {
		$('#UserEmpresa').attr('disabled', true);
		$('#UserGestore').attr('disabled', false);
		$("#UserEmpresa").val($("#UserEmpresa option:first").val());
	}
	if($('#empresa_radio0').is(':checked')) {
		$('#UserEmpresa').attr('disabled', false);
		$('#UserGestore').attr('disabled', true);
		$("#UserGestore").val($("#UserGestore option:first").val());
	}
});

 // $("#gestor_radio0").attr('disabled', 'disabled');
//$('#UserEmpresa').attr('disabled', true);
//	$('#UserGestore').attr('disabled', true);
	
</script>