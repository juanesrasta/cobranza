<div class="page-header" style = "text-align: center;">
    <h1> Confirmación de Pago <br>  <small></small></h1>
</div>
<div class = "search_box" style= "margin: 1%;">
    <fieldset class = "confirmar">
        <fieldset class = 'comision_busqueda_general'>
            <?php
            echo $this->Form->create('Pago');
            if (!empty($gestor_id)) {
                $val = $gestor_id;
            } else {
                $val = '';
            }
            echo $this->Form->input('gestor_id', array('label' => 'Gestor ', 'empty' => 'Todos','id' => 'select_gestor','value' => $val));
            if (!empty($cliente_id)) {
                $val = $cliente_id;
            } else {
                $val = '';
            }
            echo $this->Form->input('cliente_id', array('label' => 'Empresa ', 'empty' => 'Todas','id' => 'empresa_select','value' => $val));
            echo $this->Form->input('productos',array('type' => 'hidden','id' => 'input_productos'));
            ?>
        </fieldset>
        <div class="productos_empresa">
            <?php
            if (!empty($productos_clientes)) {
                foreach ($productos_clientes as $k => $p) {
                    $input = '<input class="check_productos" type="checkbox" ';
                    if (in_array($k, $productos_seleccionados)) {
                        $input = $input.'checked';
                    }
                    $input = $input.' name="data[productos][]" id = "'.$k.'" >'.$p.'<br>';
                    echo $input;
                }
            }
            ?>
        </div>
        <div style="margin-top:75px" class="filtro_fecha_confirmar">
            <fieldset style="width: 300px">
                <?php
                echo $this->Form->input('check_todos', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'id' => 'mostrar_todo',
                    'style'=> 'float: left;',
                    'class' => 'radio_button'
                ));
                if (!empty($check_mes)) {
                    $val = true;
                } else {
                    $val = false;
                }
               echo '<div style="float:left;width:91px;margin-top:2px">Todas las fechas</div>';
                echo $this->Form->input('check_por_mes', array(
                    'type' => 'checkbox',
                    'options' => array(''),
                    'label' => false,
                    'id' => 'mostrar_mes',
                    'style'=> 'float: left;',
                    'class' => 'radio_button',
                    'checked' => $val
                ));
                if (!empty($mes_id)) {
                    $val = $mes_id;
                } else {
                    $val = '';
                }
                echo $this->Form->input('mes', array('label' => 'Por mes', 'empty' => 'Todos','value' => $val, 'options' => array(
                    '01' => 'Enero',
                    '02' => 'Febrero',
                    '03' => 'Marzo',
                    '04' => 'Abril',
                    '05' => 'Mayo',
                    '06' => 'Junio',
                    '07' => 'Julio',
                    '08' => 'Agosto',
                    '09' => 'Septiembre',
                    '10' => 'Octubre',
                    '11' => 'Noviembre',
                    '12' => 'Diciembre',
                )));
                ?>
            </fieldset>
        </div>
        <?php
        echo $this->Form->input('tipo_boton',array(
            'type' => 'hidden',
            'value' => 'buscar',
            'id' => 'tipo_boton'
        ));
        echo $this->Form->submit('Buscar',array('onClick'=> 'boton_buscar()','style' => 'float:left'));
        echo $this->Form->submit('Ver Gestiones',array('style' => 'float:right','onClick' => 'boton_ver_gestiones()')); ?>
    </fieldset>

</div>
<fieldset class="tabla_pagos"  style="padding:2px">
    <?php if (!empty($pagos_banco)) { ?>
        <legend>Pagos enviados por el banco sin confirmar</legend>
        <div class="tabla_pagos" style="height: 160px; overflow-y: scroll">
            <table class="table table-hover user_table" id = "pagos_banco" style="width:77%;float:left">
                <tr>
                    <th>
                        Cedula
                    </th>
                    <th>
                         Nombre
                    </th>
                    <th>
                        Producto
                    </th>
                    <th>
                        Cuenta
                    </th>
                    <th>
                        Fecha_Pago
                    </th>
                    <th>
                        Total_Pago
                    </th>
                    <th>
                        <?php echo $this->Form->input('seleccionar_todos',array(
                            'type' => 'checkbox',
                            'id'=> 'seleccionar_pagos',
                            'label' => false,
                        )) ?>
                    </th>
                </tr>
                <?php
                $i = 0;
                foreach($pagos_banco as $p) {
                    if ($i==0) {
                        $clase = 'seleccionado';
                    } else {
                        $clase = '';
                    }
                    ?>
                    <tr class="tr_pagos_banco <?php echo $clase?>" name="<?php echo $p['Pago']['CedulaOrif']?>">
                        <td><?php echo $p['Pago']['CedulaOrif']?></td>
                        <td><?php echo $p['Pago']['Nombre']?></td>
                        <td><?php echo $p['Pago']['Producto']?></td>
                        <td><?php echo $p['Pago']['Cuenta']?></td>
                        <td><?php echo $p['Pago']['FechaPago']?></td>
                        <td><?php echo $p['Pago']['MontoPago']?></td>
                        <td><?php
                            echo $this->Form->input('pagos',array(
                                'name' => 'pagos['.$p['Pago']['ID'].']',
                                'type' => 'checkbox',
                                'label' => false,
                                'class' => 'check_pagos'
                            ));
                            ?>
                        </td>
                    </tr>
                <?php
                    $i++;
                }?>
            </table>
            <?php
            echo '<div style="float:right; width:240px">';
            echo $this->Form->button('Eliminar Pago',array('style' => 'margin-top:11px','onClick'=> 'boton_eliminar_pagos()'));
            echo $this->Form->input('cedula',array('style'=>'width:110px','id' => 'buscar_cedula'));
            echo $this->Form->button('Buscar por Cedula',array('class'=>'input button','onClick' => 'buscar_por_cedula()'));
            echo '<br>';
            echo $this->Form->button('Eliminar viejos',array('class'=>'input button','onClick' => 'modal_eliminar_viejos()'));
            echo '</div>';
            ?>
        </div>
   <?php  } else {
        echo '<h1 style="text-align:center">'.$mensaje.'</h1>';
    } ?>

</fieldset>
<?php
if (!empty($pagos_banco)){ ?>
<fieldset style="padding:2px">
    <legend>Pagos en el sistema sin confirmar</legend>
    <div style="height: 160px;overflow-y: scroll">
        <?php
        if (!empty($clien_pagos)) { ?>
            <table class="table table-hover user_table" id ="tabla_clien_pago" style="width:77%;float:left">
                <tr>
                    <th>CedulaOrif</th>
                    <th>Nombre</th>
                    <th>Producto</th>
                    <th>Cuenta</th>
                    <th>FechaPago</th>
                    <th>TotalPago</th>
                    <th>
                        <?php echo $this->Form->input('seleccionar_todos',array(
                            'type' => 'checkbox',
                            'id'=> 'seleccionar_clien_pagos',
                            'label' => false,
                        )) ?>
                    </th>
                </tr>
                <?php
                    $i=0;
                foreach ($clien_pagos as $c) {
                    if ($i==0) {
                        $clase = 'seleccionado';
                    } else {
                        $clase = '';
                    }
                    ?>
                    <tr id = "<?php echo $c['ClienPago']['unique_id']?>" class="tr_clien_pagos <?php echo $clase?>" name="<?php echo $c['ClienPago']['unique_id']?>">
                        <td><?php echo $c['ClienPago']['CEDULAORIF']?></td>
                        <td><?php echo $clien_pagos[0]['ClienPago']['nombre']?></td>
                        <td><?php echo $c['ClienPago']['PRODUCTO']?></td>
                        <td><?php echo $c['ClienPago']['CUENTA']?></td>
                        <td class="fecha_pago" name = "<?php echo $c['ClienPago']['FECH_PAGO']?>"><?php echo $c['ClienPago']['FECH_PAGO']?></td>
                        <td class="total_pago" name ="<?php echo $c['ClienPago']['TOTAL_PAGO']?>"><?php echo $c['ClienPago']['TOTAL_PAGO']?></td>
                        <td><?php
                            echo $this->Form->input('pagos',array(
                                'name' => $c['ClienPago']['unique_id'],
                                'type' => 'checkbox',
                                'label' => false,
                                'class' => 'check_clien_pagos',

                            ));
                            ?>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </table>
            <?php
            echo '<div style="float:right; width:240px">';
            echo $this->Form->submit('Eliminar Pago del sistema',array('style' => 'margin-top:11px','onClick'=> 'boton_eliminar_clien_pagos()'));
            echo '<br>';
            echo $this->Form->submit('Editar',array('style' => 'margin-top:11px','onClick'=> 'editar_pago()'));
            echo '<br>';
            echo $this->Form->submit('Confirmar',array('style' => 'margin-top:11px','onClick'=> 'confirmar_pagos()'));
            echo '</div>';
            ?>
        <?php
        }else {
            echo '<h1 style="text-align:center">No hay pagos registrados de este deudor</h1>';
        }
        ?>
    </div>
</fieldset>
<?php } ?>
<div class="editar_pago" style="display:none">
    <?php
        echo $this->Form->input('monto_pago',array(
            'id' => 'monto_pago'
        ));
        echo $this->Form->input('fecha_pago',array(
            'id' => 'fecha_pago'
        ));
        echo $this->Form->input('id',array(
            'type' => 'hidden',
            'id' => 'dialog_id'
         ));
        echo $this->Form->submit('Editar',array('onClick' => 'dialog_editar()'));
    ?>
</div>
<div class="ver_gestiones_confirmacion" style="display:none">
    <?php echo $this->element('Pago/gestiones_modal');?>
</div>
<div class="eliminar_viejos" style="display:none">
    <?php echo $this->element('Pago/eliminar_viejos_modal');?>
</div>
<script>
    //Trabajar con varios botones
    //Botones sin submit
    function boton_sin_submit(){
        $('#tipo_boton').val('boton_sin_submit');
    }

    function dialog_editar(){
        $('#tipo_boton').val('dialog_editar');
        //Edito
        monto_pago = $('#monto_pago').val();
        fecha_pago = $('#fecha_pago').val();
        id = $('#dialog_id').val();
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{monto_pago:monto_pago,fecha_pago:fecha_pago,id:id},
            url:"<?php echo Router::url(array('action'=>'editar_clien_pago')); ?>",
            success:function(data){
                $('#'+id_clien_pagos).find('.fecha_pago').attr('name',fecha_pago);
                $('#'+id_clien_pagos).find('.total_pago').attr('name',monto_pago);
                $('#'+id_clien_pagos).find('.fecha_pago').html(fecha_pago);
                $('#'+id_clien_pagos).find('.total_pago').html(monto_pago);
                $('.editar_pago').dialog('close');
            },
            beforeSend:function(){
            },
            complete:function(){
            }

        });
    }

    //Confirmar pagos
    function confirmar_pagos(){
        $('#tipo_boton').val('confirmar_pagos');

        //Verifico que este por lo menos uno seleccionado
        id_clien_pagos = '';
        $(".check_clien_pagos").each(function(index)
        {
            if ($(this).is(':checked')) {
                id_clien_pagos += $(this).prop('name')+',';
            }
        });
        if (id_clien_pagos != '') { //Encontro un seleccionado
            //Ajax para confirmar
             $.ajax({
                type:'POST',
                dataType:'JSON',
                data:{id_clien_pagos:id_clien_pagos},
                url:"<?php echo Router::url(array('action'=>'confirmar_pagos')); ?>",
                success:function(data){
                    clien_pago = id_clien_pagos.split(',');
                    $.each(clien_pago,function(i,v){
                        id = '#'+v;
                        $(id).remove();
                    });
                },
                beforeSend:function(){
                },
                complete:function(){
                }

            });
        } else {
            alert('Debes escoger por lo menos registro para confirmar');
            return false;
        }
    }

    function boton_buscar(){
        $('#tipo_boton').val('buscar');
    }
    function editar_pago(){
        //Busco el primer registro seleccionado
        id_clien_pagos = '';
        $('#tipo_boton').val('editar_pagos');
        $(".check_clien_pagos").each(function(index)
        {
            if ($(this).is(':checked')) {
                id_clien_pagos = $(this).prop('name');
                return false;
            }
        });
        if (id_clien_pagos != '') { //Encontro un seleccionado
            //Lleno el dialog con los datos actuales
            fecha_pago = $('#'+id_clien_pagos).find('.fecha_pago').attr('name');
            $('#fecha_pago').val(fecha_pago);
            total_pago = $('#'+id_clien_pagos).find('.total_pago').attr('name');
            $('#monto_pago').val(total_pago);
            $('#dialog_id').val(id_clien_pagos);
        } else {
            alert('Debes escoger un registro para editar');
            return false;
        }

        $('.editar_pago').dialog();
        return false;
    }

    $( "#PagoAdminConfirmarForm" ).submit(function( event ) {
        if ($('#tipo_boton').val() == 'editar_pagos' ||$('#tipo_boton').val() == 'dialog_editar' ||$('#tipo_boton').val() == 'confirmar_pagos' ||$('#tipo_boton').val() == 'boton_sin_submit') {
            return false;
        }
    });

    $('#fecha_pago').datepicker({ dateFormat: 'yy-mm-dd' });

    //Cargo los productos del cliente
    $("#empresa_select").change(function(){
            if(!isNaN($(this).val())){
                $.ajax({
                    type:'POST',
                    dataType:'JSON',
                    data:{cliente_id:$("#empresa_select").val()},
                    url:"<?php echo Router::url(array('action'=>'cargar_productos_por_empresa')); ?>",
                    success:function(data){
                        check = '';
                        $.each(data,function(i,v){
                            check +='<input class="check_productos" type="checkbox" name="data[productos][]" id = "'+i+'" >'+v+'<br>';
                        });
                        check += '';
                        $(".productos_empresa").html(check);
                    },
                    beforeSend:function(){
                        $("#empresa_select").attr('disabled',true);
                    },
                    complete:function(){
                        $("#empresa_select").removeAttr('disabled');
                        //buscar_deudores();
                    }

                });
            }
    });

    //Colocar en el input los productos marcados
    $('.check_productos').change(function(){
       $('#input_productos').val('');
        val = '';
        $(".check_productos").each(function(index)
        {
            if ($(this).is(':checked')) {
                val += $(this).val();
            }
        })
        $('#input_productos').val(val);
    });
    $(".productos_empresa").delegate('.check_productos','change', function(){
        val = '';
        $(".check_productos").each(function(index)
        {
            if ($(this).is(':checked')) {
                val += $(this).prop('id')+',';
            }
        })
        $('#input_productos').val(val);
    });

    $('#mostrar_todo').change(function(){
        if($('#mostrar_todo').is(':checked')) {
            $('#mostrar_mes').attr('checked', false);
        }
    });

    $('#mostrar_mes').change(function(){
        if($('#mostrar_mes').is(':checked')) {
            $('#mostrar_todo').attr('checked', false);
        }
    });

    //Darle click a un tr de pagos del banco
    $('.tr_pagos_banco').click(function(){
        $(".tr_pagos_banco").removeClass('seleccionado');
        $(this).addClass('seleccionado');
        cedula = $(this).attr('name');
        cargar_clien_pagos(cedula);
    });

   function buscar_por_cedula() {
       $('#tipo_boton').val('boton_sin_submit');
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{cedula:$('#buscar_cedula').val()},
            url:"<?php echo Router::url(array('action'=>'cargar_pagos')); ?>",
            success:function(data){
                if (data.hay == 'Si') {
                    i = 0;
                    table = '';
                    table += '<tr><th>Cedula</th><th>Nombre</th><th>Producto</th><th>Cuenta</th><th>FechaPago</th><th>TotalPago</th><th><input type="checkbox" name="data[Pago][seleccionar_todos]" id="seleccionar_pagos"></th></tr>';
                    $.each(data.pagos_banco,function(i,v){
                        if (i == 0) {
                            clase = 'seleccionado';
                        } else {
                            clase = '';
                        }
                        table += '<tr class="tr_pagos_banco '+clase+'" name="'+ $('#buscar_cedula').val()+'">';
                        table += '<td>'+ v.Pago.CedulaOrif+'</td>';
                        table += '<td>'+ v.Pago.Nombre+'</td>';
                        table += '<td>'+ v.Pago.Producto+'</td>';
                        table += '<td>'+ v.Pago.Cuenta+'</td>';
                        table += '<td>'+ v.Pago.FechaPago+'</td>';
                        table += '<td>'+ v.Pago.MontoPago+'</td>';
                        table += '<td><input type="checkbox" name="pagos['+v.Pago.ID+']" class="check_pagos"></td>';
                        table += '</tr>'
                        i++;
                    });
                    $("#pagos_banco").html(table);
                    cargar_clien_pagos($('#buscar_cedula').val());
                } else {
                    $("#pagos_banco").html('<h1 style="text-align:center">No hay pagos no confirmados</h1>');
                }
            },
            beforeSend:function(){

            },
            complete:function(){

            }

        });
       return false;
    }

    function cargar_clien_pagos(cedula){
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{cedula:cedula},
            url:"<?php echo Router::url(array('action'=>'cargar_clien_pagos')); ?>",
            success:function(data){
                if (data.hay == 'Si') {
                    i = 0;
                    table = '';
                    table += '<tr><th>CedulaOrif</th><th>Nombre</th><th>Producto</th><th>Cuenta</th><th>FechaPago</th><th>TotalPago</th><th><input type="checkbox" name="data[Pago][seleccionar_todos]" id="seleccionar_clien_pagos"></th></tr>';
                    $.each(data.clien_pagos,function(i,v){
                        if (i == 0) {
                            clase = 'seleccionado';
                        } else {
                            clase = '';
                        }
                        table += '<tr class="'+clase+'" name="'+ v.ClienPago.unique_id+'" id = "'+v.ClienPago.unique_id+'" >';
                        table += '<td>'+ v.ClienPago.CEDULAORIF+'</td>';
                        table += '<td>'+ data.clien_pagos[0].ClienPago.nombre+'</td>';
                        table += '<td>'+ v.ClienPago.PRODUCTO+'</td>';
                        table += '<td>'+ v.ClienPago.CUENTA+'</td>';
                        table += '<td class="fecha_pago" name="'+v.ClienPago.FECH_PAGO+'">'+ v.ClienPago.FECH_PAGO+'</td>';
                        table += '<td class="total_pago" name="'+v.ClienPago.TOTAL_PAGO+'">'+ v.ClienPago.TOTAL_PAGO+'</td>';
                        table += '<td><input type="checkbox" name="'+v.ClienPago.unique_id+'" class="check_clien_pagos"></td>';
                        table += '</tr>'
                        i++;
                    });
                    $("#tabla_clien_pago").html(table);
                } else {
                    $("#tabla_clien_pago").html('<h1 style="text-align:center">No hay pago resgistrados de este deudor</h1>');
                }
            },
            beforeSend:function(){

            },
            complete:function(){

            }
        });
        return false;
    }
    function boton_eliminar_pagos(){
        $('#tipo_boton').val('eliminar_pagos');
    }
    $("#pagos_banco").delegate('#seleccionar_pagos','change', function(){
        //alert('entra');
        if ($('#seleccionar_pagos').is(':checked')) {
            $(".check_pagos").prop('checked', true);
        } else {
            $(".check_pagos").removeAttr('checked');
        }
    });

    $("#tabla_clien_pago").delegate('#seleccionar_clien_pagos','change', function(){
        //alert('entra');
        if ($('#seleccionar_clien_pagos').is(':checked')) {
            $(".check_clien_pagos").prop('checked', true);
        } else {
            $(".check_clien_pagos").removeAttr('checked');
        }
    });

    function boton_eliminar_clien_pagos(){
        $('#tipo_boton').val('boton_sin_submit');
        registros = '';
        $(".check_clien_pagos").each(function(index)
        {
            if ($(this).is(':checked')) {
                registros += $(this).prop('name')+',';
            }
        });
        $.ajax({
            type:'POST',
            dataType:'JSON',
            data:{registros:registros},
            url:"<?php echo Router::url(array('action'=>'eliminar_clien_pagos')); ?>",
            success:function(data){
                clien_pago = registros.split(',');
                $.each(clien_pago,function(i,v){
                   id = '#'+v;
                    $(id).remove();
                    });
            },
            beforeSend:function(){

            },
            complete:function(){

            }
        });
        return false;
    }

</script>