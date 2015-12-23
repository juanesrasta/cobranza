<?php
App::uses('AppModel', 'Model');

class Producto extends AppModel {
 public $name = 'Producto';

	public $validate = array(
        'producto' => array(
            'rule' => 'notEmpty'
        ),
		
		'descripcion' => array(
            'rule' => 'notEmpty'
        )
    );
	
	public $belongsTo = array(
        'Cliente' => array(
            'className'    => 'Cliente',
            'foreignKey'   => 'cliente_id'
        ),
		 'GruposProducto' => array(
            'className'    => 'GruposProducto',
            'foreignKey'   => 'gruposProducto_id'
        ),
    );
	
	function buscarProductos($cedula,$empresas){
		$empresas = Set::combine($empresas, '{n}.Cliente.id', '{n}.Cliente.rif');
		foreach ($empresas as $e) {
			$productos[$e] = $this->find('all', array(
				'fields' => array('ClienProd.*', 'Producto.*'),
				'joins' => array(
					array(
						'table' => 'clien_prod',
						'alias' => 'ClienProd',
						'type' => 'INNER',
						'conditions' => array(
							'ClienProd.cedulaorif' => $cedula,
							'ClienProd.cod_prod = Producto.codigo',
							'ClienProd.RIF_EMP' => $e
						),
					),
				),
			));
		}
		return $productos;
	}
	
	function buscarPagos($cedula,$empresas){
		$empresas = Set::combine($empresas, '{n}.Cliente.id', '{n}.Cliente.rif');
		foreach ($empresas as $e) {
			$pagos[$e] = $this->find('all', array(
				'fields' => array('ClienPago.*'),
				'joins' => array(
					array(
						'table' => 'clien_pagos',
						'alias' => 'ClienPago',
						'type' => 'INNER',
						'conditions' => array(
							'ClienPago.cedulaorif' => $cedula,
							'ClienPago.RIF_EMP' => $e,
							'ClienPago.cod_prod = Producto.codigo'
						),
					),
				),
			));
		}
		return $pagos;
	}	
}
?>