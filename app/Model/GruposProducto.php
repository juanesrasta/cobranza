<?php
App::uses('AppModel', 'Model');

class GruposProducto extends AppModel {
 public $name = 'GruposProducto';
	
	var $hasOne  = array(
		'Producto' =>
			array('className' => 'Producto',
				 'foreignKey' => 'gruposProducto_id',
			)
	);
		
	 public $validate = array(
        'aIntPorcen' => array(
            'decimal' => array(
                'rule' => array('ValidarDecimal'),
                'message' => 'El gestor interno debe ser númerico y decimal'
            )
        ),
        'bIntPorcen' => array(
            'decimal' => array(
                'rule' => array('ValidarDecimal'),
                'message' => 'El gestor interno debe ser númerico y decimal'
            )
        ),
		'cIntPorcen' => array(
            'decimal' => array(
                'rule' => array('ValidarDecimal'),
                'message' => 'El gestor interno debe ser númerico y decimal'
            )
        ),
		'dIntPorcen' => array(
            'decimal' => array(
                'rule' => array('ValidarDecimal'),
                'message' => 'El gestor interno debe ser númerico y decimal'
            )
        ),
		'aExtPorcen' => array(
            'decimal' => array(
                'rule' => array('ValidarDecimal'),
                'message' => 'El gestor externo debe ser númerico y decimal'
            )
        ),
		'bExtPorcen' => array(
            'decimal' => array(
                'rule' => array('ValidarDecimal'),
                'message' => 'El gestor externo debe ser númerico y decimal'
            )
        ),
		'cExtPorcen' => array(
            'decimal' => array(
                'rule' => array('ValidarDecimal'),
                'message' => 'El gestor externo debe ser númerico y decimal'
            )
        ),
		'dExtPorcen' => array(
            'decimal' => array(
                'rule' => array('ValidarDecimal'),
                'message' => 'El gestor externo debe ser númerico y decimal'
            )
        ),
    );
	
	function ValidarDecimal($data){
		foreach ($data as $d) {
			if (strpos($d,'.') === false) {
				return false;
			} 
		}
		return true;
	}
	
}

?>