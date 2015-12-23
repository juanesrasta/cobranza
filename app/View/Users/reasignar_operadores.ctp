<div class="page-header" style = "text-align: center;">
	<h1>Reasignando supervisor a los operadores<br></h1>
</div>
<div>
<?php
	echo $this->Form->create('User',array('id' => 'formReasignar')). '<br><br>';
	echo $this->Form->input('supervisor_viejo',array(
		'type' => 'hidden',
		'label' => false,
		'value' => $supervisor_viejo_id
	));
		
?>
<div class = "search_box" style= "margin: 1%;">
<fieldset>
	<legend> Selecciona el nuevo supervisor </legend>
<?php
	if (!empty($supervisors)) {
		echo $this->Form->input('supervisor_id',array(
			'label' => 'Supevisor',
		));
	} else {
		echo 'NO HAY SUPERVISORES ACTIVOS';
	}
?>
</fieldset>
</div>
<table class="table table-hover user_table">
		<tr>
			<th>
			<?php
			echo $this->Form->input('seleccionar_todos',array(
				'name' => 'seleccionar_todos',
				'type' => 'checkbox',
				'label' => false,
				'style' => 'float:left',
				'id' => 'seleccionar_todos'
			));
			?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('username','Usuario'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('nombre_completo','Nombre'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('status','Status'); ?>
			</th>
			<th>
			<?php echo $this->Paginator->sort('tipo','Tipo'); ?>
			</th>
		</tr>
<?php
	foreach($operadores as $o){ ?>
		<tr>
			<td>
				<?php
				echo $this->Form->input('seleccionar',array(
					'name' => 'operador['.$o['User']['id'].']',
					'type' => 'checkbox',
					'label' => false,
					'style' => 'float:left',
					'class' => 'operador_seleccionado',
				)); ?>
			</td>
			<td>
				<?php echo $o['User']['username']; ?>
			</td>
			<td>
				<?php echo $o['User']['nombre_completo']; ?>
			</td>
			<td>
				<?php echo $o['User']['status']; ?>
			</td>
			<td>
				<?php echo $o['User']['tipo']; ?>
			</td>
		</tr>
		
<?php
	}
?>

</table>

<div class="listas index grid_12">
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('Antetior'), array(), null, array('class' => 'prev disabled'));
		echo  ' ';
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo  ' ';
		echo $this->Paginator->next(__('Siguiente') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<?php
	if (!empty($supervisors)) {
		echo $this->Form->submit('Reasignar supervisor', array('style'=>'float:right;margin-right:20px', 'name' => 'aceptar_form'));
		echo $this->Form->end();
	} else {
		echo '<span style="float:right;margin-right:40px">NO HAY SUPERVISORES ACTIVOS</span>';
	}
?>
<script>
	$("#seleccionar_todos").change(function () {
		if ($(this).is(':checked')) {
			$("input[type=checkbox].operador_seleccionado").prop('checked', true); //todos los check seleccionados
		} else {
			$("input[type=checkbox].operador_seleccionado").prop('checked', false); //todos los check deseleccionados
		}
	});
	$( "#formReasignar" ).submit(function( event ) {
		count = 0;
		$('input[type=checkbox].operador_seleccionado').each(function () {
			if ($(this).is(':checked')) {
				count++;
			}
		});
		if (count > 0) {
			return true;
		}
		alert('Debe seleccionar los operadores que desea reasignar');
		return false;
	});
</script>