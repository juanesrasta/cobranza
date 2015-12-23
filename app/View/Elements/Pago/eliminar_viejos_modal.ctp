<?php
echo $this->Form->input('mes_eliminar_id',array(
    'options' => $meses,
    'id' => 'eliminar_por_mes',
    'label' => 'Eliminar pagos del mes'
));
echo $this->Form->submit('Eliminar',array('onClick' => 'eliminar_viejos()','style'=> 'margin-top:20px'));
?>
<script>
    function modal_eliminar_viejos(){
        boton_sin_submit();
        $('.eliminar_viejos').dialog();
    }
    function eliminar_viejos(){
        boton_sin_submit();
        mes = $('#eliminar_por_mes').val();
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{mes:mes},
            url:"<?php echo Router::url(array('action'=>'eliminar_pagos_viejos')); ?>",
            success:function(data){
                $('.eliminar_viejos').dialog('close');
            },
            beforeSend:function(){
            },
            complete:function(){
            }

        });
    }
</script>