	<div class = "grid_4">&nbsp;</div>
	<div class = "row login grid_8 ">
	
		<fieldset>
		<legend> Editar Usuario </legend>
	<?php 
		echo $this->Form->create('User', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
	?>   
			(*) Campos obligatorios <br><br>
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1 ">
			  <?php
				echo $this->Form->input('username',array(
					'label' => ' Nombre de Usuario *',
					'placeholder' => 'Username',
					'class' => 'form-control'
				));
				echo $this->Form->input('id',array(
					'label' => false,
					'type' => 'hidden',
					'value' => $id
				));
			  ?>
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1">
				<?php 	if($permisos_usuarios['Permiso']['usuarios']){
							echo $this->Form->input('pass',array(
								'label' => 'Contraseña *',
								'placeholder' => 'Contraseña',
								'class' => 'form-control',
								'type' => 'text'
							));
						}	
				?>
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1">
				<?php
					echo $this->Form->input('nombre_completo',array(
						'label' => 'Nombre Completo',
						'placeholder' => 'Nombre',
						'class' => 'form-control'
					));
				?>
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1">
				<?php
					 echo $this->Form->input('status', array(
							'options' => array('activo' => 'Activo', 'inactivo' => 'Inactivo'),
							'class' => 'form-control'
						));
				?>
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1">
				<?php
					echo $this->Form->input('tipo', array(
						'options' => array('interno' => 'Interno', 'externo' => 'Externo'),
						'class' => 'form-control'
					));
				?>
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1">
				<?php
					// echo $this->Form->input('cedula',array(
						// 'label' => 'Cédula',
						// 'placeholder' => 'Cedula',
						// 'class' => 'form-control'
					// ));
				?>
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-10 col-md-offset-1">
				<?php
					// echo $this->Form->input('telefono',array(
						// 'label' => 'Teléfono',
						// 'placeholder' => 'Teléfono',
						// 'class' => 'form-control'
					// ));
				?>
			</div>
		  </div>
		<div class="form-group">
			<div class="col-sm-10 col-md-offset-1">
				<?php 	if($permisos_usuarios['Permiso']['usuarios']){
							echo $this->Form->input('rol', array(
								'options' => array('administrador' => 'Administrador', 'supervisor' => 'Supervisor', 'operador' => 'Operador'),
								'class' => 'form-control'
							));
						}	
				?>
			</div>
		  </div>
		  <div class="form-group" id = "input_supervisor" style="display:none">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('supervisor_id',array(
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
		  <div class="form-group">
			<div class="col-sm-offset-1 col-sm-10 text-center">
			 <?php 
				echo $this->Form->submit(__('Guardar'), array('class' => 'btn btn-success btn-block'));
				echo $this->Form->end;
			 ?>
			</div>
		  </div>
		  </fieldset>
</div>
<script>
	$( document ).ready(function() {
	verificar_rol();
});

$( "#UserRol" ).change(function() {
	verificar_rol();
});

function verificar_rol() {
	if ($( "#UserRol" ).val() == 'operador') {
		$('#input_supervisor').css('display','block');
	} else {
		$('#input_supervisor').css('display','none');
	}
}
</script>