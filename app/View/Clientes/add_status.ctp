<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Agregar </legend>
		(*) Campos obligatorios  <br><br>
		<?php 
			echo $this->Form->create('Statu', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
		?>

			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('codigo',array(
							'label' => 'Codigo *',
							'placeholder' => 'Codigo',
							'class' => 'form-control',
							'type' => 'text',
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('condicion',array(
							'label' => 'Condicion',
							'placeholder' => 'Condicion',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						 echo $this->Form->input('status', array(
							'options' => array(1 => 'Activo', 0 => 'Inactivo'),
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			  
			   <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('rif_emp',array(
                            'type' => 'select',
							'label' => 'Cliente *',
							'class' => 'form-control',
                            'options' => $clientes
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
</div>

