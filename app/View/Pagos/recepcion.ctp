<div class="page-header" style = "text-align: center;">
	<h1> RECEPCION DE PAGOS <br>  <small></small></h1>
</div>

<div class = "search_box">
	<fieldset class = "gest_status_wrapper recepcion_pago">
	<?php echo $this->Form->create('Pagos', array('type'=> 'file')); ?>
		<div class="" style="">	
			<?php
				echo $this->Form->input('Pago.banco',array('options' => $bancos));
			?>

			<div style="float: right"> Fecha: <?php echo date('d/m/Y'); ?> </div>	
		<div>		

		<div style="margin-top: 10px;">
			<?php
				echo $this->Form->file('archivo',array(
					'label' => 'Selecciona un archivo',
                    'id' => 'archivo',
                    'style' => 'margin-left:95px'
				));
			?> 
		</div>  
		<div style="margin-top: 10px; margin-left: 95px; "> <?php echo $this->Form->submit(__('Procesar')); ?> </div>
	</fieldset>

</div>

<script>
    $("#PagosRecepcionForm").submit(function (){
        extension = $('#archivo').val().split('.');
        cliente = $('#cliente').val();
        if (extension[2] == undefined) {
            alert('Debe seleccionar algún archivo');
            return false;
        }
        if (cliente == 7) {
            if (extension[2] == 'xls') {
                return true;
            } else {
                alert('Formato de archivo invalido');
                return false;
            }
        } else if(cliente == 11){
            if (extension[2] == 'xlsx') {
                return true;
            } else {
                alert('Formato de archivo invalido');
                return false;
            }
        } else {
            alert('Debe seleccionar un banco');
            return false;
        }

    });
    function funcion(){
        alert($('#archivo').val());
    }
</script>