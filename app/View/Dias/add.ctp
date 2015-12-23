<div class = " grid_16 ">
<div class = "grid_4">&nbsp;</div>
<div class = "row login grid_8 ">
	<fieldset>
		<legend> Agregar Fecha No Laborable </legend>
		(*) Campos obligatorios  <br><br>
		<?php 
			echo $this->Form->create('Dia', array('class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off'));
		?>
			
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1 ">
				  <?php
					echo $this->Form->input('fecha',array(
						'label' => 'Fecha *',
						'placeholder' => 'Fecha',
						'class' => 'form-control',
						'id' => 'pickDate',
						'type' => 'text',
						'readonly' => 'true'
					));
				  ?>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-10 col-md-offset-1">
					<?php
						echo $this->Form->input('descripcion',array(
							'label' => 'Descripción *',
							'placeholder' => 'Descripción',
							'class' => 'form-control',
							// 'type' => 'text',
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

<script>
$('#pickDate').datepicker({
    dateFormat: "yy-mm-dd",
    // beforeShowDay: $.datepicker.noWeekends,
    // minDate: new Date(2012, 10 - 1, 25)
	beforeShowDay: disableSpecificDaysAndWeekends
});

function disableSpecificDaysAndWeekends(date) {
	var disabledSpecificDays = [];
	 
	<?php foreach ($fechas as $f){ ?>
			disabledSpecificDays.push(<?php echo json_encode($f)?>);
		<?php }
	?>

	console.debug(disabledSpecificDays);
    // var m = date.getMonth();
	var m = ("0" + (date.getMonth() + 1)).slice(-2)
    // var d = date.getDate();
	var d = ("0" + date.getDate()).slice(-2);
    var y = date.getFullYear();
	
	var fecha = y + '-' + (m) + '-' + d;
	console.debug(fecha);
	
	
    for (var i = 0; i < disabledSpecificDays.length; i++) {
        if ($.inArray(fecha, disabledSpecificDays) != -1 || new Date() > date) {
            return [false];
        }
    }

    var noWeekend = $.datepicker.noWeekends(date);
    return !noWeekend[0] ? noWeekend : [true];
}
</script>