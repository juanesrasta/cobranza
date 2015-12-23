<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Agregar mis datos </legend>
		(*) Campos obligatorios  <br><br>
		<?php 
			echo $this->Form->create('Cliente', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
		?>
			
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 ">
				  <?php
					echo $this->Form->input('nombre',array(
						'label' => 'Nombre',
						'placeholder' => 'Nombre',
						'class' => 'form-control'
					));
				  ?>
				</div>
			  </div>
			    <div class="form-group">
			<div class="col-sm-10 col-md-offset-1 ">
			  <?php
				echo $this->Form->input('id',array(
					'label' => ' RIF *',
					'placeholder' => 'RIF',
					'class' => 'form-control'
				));
			  ?>
			</div>
		  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('contacto',array(
							'label' => 'Cont&aacute;cto',
							'placeholder' => 'Contacto',
							'class' => 'form-control',
							'type' => 'text',
						));
					?>
						
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('cargo',array(
							'label' => 'Cargo',
							'placeholder' => 'Cargo',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('departamento',array(
							'label' => 'Departamento',
							'placeholder' => 'Departamento',
							'class' => 'form-control'
						));
					?>
				</div>
			  </div>
			   <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('n_caracteres',array(
							'label' => 'Cantidad de Caracteres',
							'placeholder' => 'Cantidad',
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
				<div class="col-sm-offset-1 col-sm-10 text-center">
				 <?php 
					echo $this->Form->submit(__('Agregar Cliente'), array('class' => 'btn btn-success btn-block'));
					echo $this->Form->end;
				 ?>
				</div>
			  </div>
		</fieldset>
	</div>
</div>


