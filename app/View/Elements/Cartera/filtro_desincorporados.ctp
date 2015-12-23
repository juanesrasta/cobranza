<div class="filtro_gestion">
<div class = "inner_left">
	<?php
        echo '<div style="float:left">';
        echo $this->Form->create('Cartera');
		echo $this->Form->input('cliente_id',array(
			'label' => 'Empresa',
			'class' => 'form-control',
			'empty' => 'TODOS',
			'id' => 'select_cliente'
		));

        echo $this->Form->input('statu_id',array(
            'class' => 'form-control',
            'id' => 'select_statu',
            'empty' => 'TODOS',
            'label' => 'Status',
            //'options' => $tipos_status
        ));
		
		echo $this->Form->input('fecha', array('label' => 'Fecha Desincorpocacion  ', 'class' => 'fecha_asignacion','empty' => 'Todos','id' => 'pickDate','type' => 'text','readonly' => 'true'));
	    echo '</div>';
        echo '<div class="busqueda_personalizada_desincorporados" style="width:280px">';
        echo $this->Form->input('busqueda_personalizada',array('type' => 'radio','options'=>array('cedulaOrif','nombre')));
        echo $this->Form->input('text_busqueda_personalizada',array('label'=> false, 'style' => 'position:absolute;margin-left:80px;bottom:165px'));
        echo '</div>';
        echo $this->Form->submit('Consultar');
    ?>
</div>
</div>
<script>
    $('.fecha_asignacion').datepicker({
        dateFormat: "dd-mm-yy",
    });

    $('#select_cliente').change(function(){
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{cliente_id:$("#select_cliente").val()},
            url:"<?php echo Router::url(array('controller' => 'cartera','action'=>'cargar_status_por_empresa')); ?>",
            success:function(data){
                options_p = "<option value = ''>TODOS</option>";
                $.each(data, function (i, v) {
                    options_p += "<option value='" + i + "'>" + v + "</option>";
                });
                $("#select_statu").html(options_p);
            },
            beforeSend:function(){
                $("#select_cliente").attr('disabled',true);
            },
            complete:function(){
                $("#select_cliente").removeAttr('disabled');
            }

        });
    });
</script>