<?php
App::uses('AppModel', 'Model');
class ClienPago extends AppModel {
    var $primaryKey = 'unique_id';

    function buscarGestionesConfirmadasParaComision($condiciones_p = null){
        $condiciones = array('ClienPago.EST_PAGO' => 'confirmado');
        if (!empty($condiciones_p)) {
            array_push($condiciones, $condiciones_p);
        }
        $arreglo_comisiones = $this->find('all',array(
            'fields' => array('ClienPago.*','Gestor.*','User.*','Producto.*','GruposProducto.*'),
            'joins' => array(
                array(
                    'table' => 'gestors',
                    'alias' => 'Gestor',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.LOGIN_REG = Gestor.Clave',
                    )
                ),

                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = Gestor.user_id',
                    )
                ),
                array(
                    'table' => 'productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.COD_PROD = Producto.codigo',
                        'ClienPago.RIF_EMP = Producto.rif_emp'
                    )
                ),
                array(
                    'table' => 'grupos_productos',
                    'alias' => 'GruposProducto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.gruposProducto_id = GruposProducto.id',
                    )
                )
            ),
            'conditions' => $condiciones,
        ));
        return ($arreglo_comisiones);
    }

    function buscarGestiones($condiciones_p = null){
        $condiciones = array('');
        if (!empty($condiciones_p)) {
            array_push($condiciones, $condiciones_p);
        }
        $arreglo_comisiones = $this->find('all',array(
            'fields' => array('ClienPago.*','Gestor.*','User.*','Producto.*','GruposProducto.*'),
            'joins' => array(
                array(
                    'table' => 'gestors',
                    'alias' => 'Gestor',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.LOGIN_REG = Gestor.Clave',
                    )
                ),

                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = Gestor.user_id',
                    )
                ),
                array(
                    'table' => 'productos',
                    'alias' => 'Producto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.COD_PROD = Producto.codigo',
                        'ClienPago.RIF_EMP = Producto.rif_emp'
                    )
                ),
                array(
                    'table' => 'grupos_productos',
                    'alias' => 'GruposProducto',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Producto.gruposProducto_id = GruposProducto.id',
                    )
                )
            ),
            'conditions' => $condiciones,
        ));
        return ($arreglo_comisiones);
    }

    function buscarPagos($condiciones_p = null){
       $condiciones = array('');
        if (!empty($condiciones_p)) {
            array_push($condiciones, $condiciones_p);
        }

         $pagos = $this->find('all',array( 
            'fields' => array('ClienPago.*','Cliente.nombre','Data.Nombre'),
            'conditions' => $condiciones,
            'joins' => array(
                 array(
                    'table' => 'clientes',
                    'alias' => 'Cliente',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.RIF_EMP = Cliente.rif',
                    )
                ),
                 array(
                    'table' => 'gestors',
                    'alias' => 'Gestor',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.LOGIN_REG = Gestor.Clave',
                    )
                ),
                 array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = Gestor.user_id',
                    )
                ),
                 array(
                    'table' => 'data',
                    'alias' => 'Data',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.CEDULAORIF = Data.CedulaOrif',
                    )
                ),

             )
        ));

        return ($pagos);

    }

    function buscarPagosLiquidacion($condiciones_p = null){
       $condiciones = array('');
        if (!empty($condiciones_p)) {
            array_push($condiciones, $condiciones_p);
        }

         $pagos = $this->find('all',array( 
            'fields' => array('ClienPago.*','Cliente.nombre','Data.Nombre', 'ClienNrocliente.*', 'Cobranza.*', 'Status.*'),
            'conditions' => $condiciones,
            'joins' => array(
                 array(
                    'table' => 'clientes',
                    'alias' => 'Cliente',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.RIF_EMP = Cliente.rif',
                    )
                ),
                 array(
                    'table' => 'gestors',
                    'alias' => 'Gestor',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.LOGIN_REG = Gestor.Clave',
                    )
                ),
                 array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = Gestor.user_id',
                    )
                ),
                 array(
                    'table' => 'data',
                    'alias' => 'Data',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.CEDULAORIF = Data.CedulaOrif',
                    )
                ),
                array(
                    'table' => 'clien_nroclientes',
                    'alias' => 'ClienNrocliente',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.CEDULAORIF = ClienNrocliente.CedulaOrif',
                    )
                ),
                array(
                    'table' => 'cobranzas',
                    'alias' => 'Cobranza',
                    'type' => 'INNER',
                    'limit' => 1,
                    'conditions' => array(
                        'ClienPago.CEDULAORIF = Cobranza.CEDULAORIF',
                        'Cobranza.RIF_EMP = 11'
                    )
                ),
                array(
                    'table' => 'status',
                    'alias' => 'Status',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ClienPago.COND_PAGO  = Status.codigo',
                        'Status.RIF_EMP = 11'
                    )
                )

             )
        ));

        return ($pagos);

    }

}

?>