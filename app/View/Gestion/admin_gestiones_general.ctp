<div class="page-header" style = "text-align: center;">
	<h1> Consulta General de Gestiones<br>  <small></small></h1>
</div>


<div class = "search_box" style= "margin: 1%;">
	<fieldset class = "gest_status_wrapper">
		<fieldset class = 'gest_status'>
			<?php
				// debug($deudores);
			
				echo $this->Form->create(null, array(
			'url' => array_merge(array('controller' => 'gestion', 'action' => 'admin_gestiones_general'), $this->params['pass'])
		));?>
				<label id="LabelSupervisor" for="LabelSupervisor">Supervisor </label>
				<select name="supervisor" id="UserSupervisor" style="margin-bottom: 5px;">
					<option value="">Todas</option>
					<?php foreach($supervisors as $spvisor){?>
						<option value="<?=$spvisor['gestors']['id'];?>"><?=$spvisor['gestors']['Nombre'];?></option>
					<?php } ?>
				</select>
			<?php	
				echo $this->Form->input('gestore', array('label' => 'Gestor ', 'empty' => 'Todos'));
				echo $this->Form->input('empresa', array('label' => 'Empresa ', 'empty' => 'Todas'));
				echo $this->Form->input('statu', array('label' => 'Status ', 'empty' => 'Todos'));				
				
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
			echo $this->Form->submit(('Buscar'), array('style' => 'float: right;'));
		echo '</td><td>';
			// echo $this->Form->button(__('Cerrar'), array('style' => 'float: left;', 'class' => 'boton_cerrar'));
		echo '</td></tr><tr><td colspan="4">';
		echo $this->Form->input('buscar', array('label' => 'Cédula o Rif, Deudor o Teléfono ', 'empty' => 'Todos')).'</td></tr></table>';
		
		echo $this->Form->end();
?>
</fieldset>

</div>

<table class="table table-hover user_table">
		<tr>
			<th>
				Fecha
			</th>
			<th>
				Tipo de Gestión
			</th>
			<th>
				Cedula o Rif
			</th>
			<th>
				Deudor
			</th>
			<th>
				Teléfono
			</th>
			<th>
				Proxima G.
			</th>
			<th>
				Gestor
			</th>
			<th>
				Empresa
			</th>
			<th>
				Observación1
			</th>
			<th>
				Observación2
			</th>
		</tr>
<?php
	// debug($consultas);
	foreach($consultas as $consulta){ ?>
		
		<tr>
			<td>
				<?php 
					$fecha = explode("-",$consulta['ClienGest']['fecha']);
					$fecha1 = $fecha[0]."-".$fecha[1];
					$fech2=explode(" ",$fecha[2]);
					echo $fecha1."-".$fech2[0];
				?>
			</td>
			<td>
				<?php 
					echo $consulta['ClienGest']['cond_deud'];
				?>
			</td>
			<td>
				<?php
					echo $consulta['ClienGest']['cedulaorif'];
				?>
			</td>
			<td>
				<?php
					echo $consulta['Cobranza']['NOMBRE'];			
				?>
			</td>
			<td>
				<?php
					echo $consulta['ClienGest']['telefono'];	
				?>
			</td>
			<td>
				<?php
					echo $consulta['ClienGest']['proximag'];
				?>
			</td>
			<td>
				<?php
					echo $consulta['ClienGest']['gest_asig'];
				?>
			</td>
			<td>
				<?php
					echo $consulta['Cliente']['nombre'];
				?>
			</td>
			<td>
				<?php
					echo $consulta['ClienGest']['observac'];
				?>
			</td>
			<td>
				<?php
					echo $consulta['ClienGest']['Observac1'];
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
	
</script>