<fieldset>
    <legend>Datos del deudor</legend>
    <div class="datos_del_deudor">
        Cédula:<br>
        Nombre:<br>
        Gestor:
    </div>
</fieldset>
<fieldset class="fieldset_gestiones_confirmacion">
    <legend>Gestiones</legend>
    <div class="gestiones_confirmacion">
        <table id ="gestiones_g" class="table_gestiones_confirmacion table" width="280px">
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr><tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>

        </table>
    </div>
    <div class = "comentario_gestiones" style = "float: right;margin-top: 14px;margin-left: 12px;">
        <?php
        echo $this->Form->input('Comentarios', array(
            'class' => 'large_input input_comentario',
            'type' => 'textarea',
            'value' => '',
            'id' => 'input_comentario',
            'label' => false
        ));
        ?>
        <?php
        echo $this->Form->input('Comentarios', array(
            'class' => 'large_input',
            'type' => 'textarea',
            'value' => '',
            'id' => 'input_comentario2',
            'label' => false
        ));
        ?>
    </div>
</fieldset>
<fieldset class="fieldset_gestiones_confirmacion">
    <legend>Estado de Cuenta</legend>
    <div class="gestiones_confirmacion">
        <table id = "pagos_g" class="table_gestiones_confirmacion table" width="280px">
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr><tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>

        </table>
    </div>
    <div class = "comentario_estado" style="margin-left:20px">
        <?php
        echo $this->Form->input('Saldo Inicial', array(
            'class' => 'estado_input'
        ));
        echo $this->Form->input('Saldo Actual', array(
            'class' => 'estado_input'
        ));
        echo $this->Form->input('Pagos', array(
            'class' => 'estado_input',
            'id' => 'total_pagos'
        ));
        ?>
    </div>
</fieldset>
<fieldset class="fieldset_gestiones_confirmacion">
    <legend>Productos</legend>
    <div class="gestiones_confirmacion" style="width: 545px">
        <table id = "productos_g" class="table_gestiones_confirmacion table" width="280px">
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>
            <tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr><tr><td style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td  style="width:30px;height:15px"></td><td style="width:30px;height:15px"></td></tr>

        </table>
    </div>
</fieldset>
<script>
function boton_ver_gestiones(){
    boton_sin_submit();
    //Funcion ajax para buscar la informacion de las gestiones y cargarla en las tablas
    cedula = $('#pagos_banco .seleccionado').attr('name');
    $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{cedula:cedula},
        url:"<?php echo Router::url(array('action'=>'buscar_gestiones')); ?>",
        success:function(data){
            //Lleno los datos del deudor
            datos_deudor = 'Cedula: '+cedula+'<br>Nombre: '+data.nombre.Cobranza.NOMBRE+'<br>Gestor: '+data.gestiones[0].ClienGest.gest_asig;
            $('.datos_del_deudor').html(datos_deudor);
            //Lleno la tabla gestiones
            tabla_gestiones = '<tr><th>Nro</th><th>Telefono</th><th>Cond_deud</th><th>Proxima Gestion</th><th>Gestor</th></tr>'
            $.each(data.gestiones,function(i,v){
                if (i == 0) {
                    clase = 'seleccionado';
                } else {
                    clase = '';
                }
                tabla_gestiones += '<tr class="tr_gestiones '+clase+'" name="'+ v.ClienGest.id+'">';
                tabla_gestiones += '<td>'+ v.ClienGest.numero+'</td>';
                tabla_gestiones += '<td>'+ v.ClienGest.telefono+'</td>';
                tabla_gestiones += '<td>'+ v.ClienGest.cond_deud+'</td>';
                tabla_gestiones += '<td>'+ v.ClienGest.proximag+'</td>';
                tabla_gestiones += '<td>'+ v.ClienGest.gest_asig+'</td>';
                tabla_gestiones +='</tr>';
            });
            $('#gestiones_g').html(tabla_gestiones);
            $('#input_comentario').val(data.gestiones[0].ClienGest.observac);
            $('#input_comentario2').val(data.gestiones[0].ClienGest.Observac1);
            //Lleno la tabla de pagos
            tabla_pagos = '<tr><th>Fech_Reg</th><th>Fech_Pago</th><th>Total_Pago</th><th>Producto</th><th>Cuenta</th><th>Est_pago</th></tr>'
            $.each(data.pagos,function(i,v){
                tabla_pagos += '<tr>';
                tabla_pagos += '<td>'+ v.ClienPago.FECH_REG+'</td>';
                tabla_pagos += '<td>'+ v.ClienPago.FECH_PAGO+'</td>';
                tabla_pagos += '<td>'+ v.ClienPago.TOTAL_PAGO+'</td>';
                tabla_pagos += '<td>'+ v.ClienPago.PRODUCTO+'</td>';
                tabla_pagos += '<td>'+ v.ClienPago.CUENTA+'</td>';
                tabla_pagos += '<td>'+ v.ClienPago.EST_PAGO+'</td>';
                tabla_pagos +='</tr>';
            });
            $('#pagos_g').html(tabla_pagos);
            console.debug(data);
            $('#PagoSaldoInicial').val(data.saldoInicial);
            $('#PagoSaldoActual').val(data.saldoActual);
            $('#total_pagos').val(data.total_pago);

            //Lleno la tabla de productos
            tabla_productos = '<tr><th>Producto</th><th>Cuenta</th><th>Saldo Vencido</th><th>Dias Mora</th><th>Saldo Inicial</th><th>Descripcion</th></tr>'
            $.each(data.productos,function(i,v){
                if (i == 0) {
                    clase = 'seleccionado';
                } else {
                    clase = '';
                }
                tabla_productos += '<tr class="tr_productos '+clase+'" name="'+ v.ClienProd.unique_id+'">';
                tabla_productos += '<td>'+ v.ClienProd.COD_PROD+'</td>';
                tabla_productos += '<td>'+ v.ClienProd.CUENTA+'</td>';
                tabla_productos += '<td>'+ v.ClienProd.SALDO_VENC+'</td>';
                tabla_productos += '<td>'+ v.ClienProd.DIASMORA+'</td>';
                tabla_productos += '<td>'+ v.ClienProd.SaldoInicial+'</td>';
                tabla_productos += '<td>'+ v.ClienProd.PRODUCTO+'</td>';
                tabla_productos +='</tr>';
            });

            $('#productos_g').html(tabla_productos);

        },
        beforeSend:function(){
        },
        complete:function(){
        }

    });
    $('.ver_gestiones_confirmacion').dialog();
}

$("#gestiones_g").delegate('.tr_gestiones','click', function(){
    //Cambiar la clase seleccionado
    $(".tr_gestiones").removeClass('seleccionado');
    $(this).addClass('seleccionado');
    //Modificcar los comentarios
    id = $(this).attr('name');
    $.ajax({
        type:'POST',
        dataType:'JSON',
        data:{gestion_id:id},
        url:"<?php echo Router::url(array('controller' => 'gestion','action'=>'cargar_info_comentario')); ?>",
        success:function(data){
            $('#input_comentario').val(data.observacion);
            $('#input_comentario2').val(data.comentario2);
        },
        beforeSend:function(){
        },
        complete:function(){
        }

    });
});
</script>