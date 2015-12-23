<?php
App::uses('AppModel', 'Model');

class Cliente extends AppModel {
 public $name = 'Cliente';

    var $primaryKey = 'rif';

	public $validate = array(
        'nombre' => array(
            'rule' => 'notEmpty'
        ),
		 'rif' => array(
            'rule' => 'notEmpty'
        ),
		'contacto' => array(
            'rule' => 'notEmpty'
        ),
		'departamento' => array(
            'rule' => 'notEmpty'
        ),
		
        'cargo' => array(
            'rule' => 'notEmpty'
        ),
		'n_caracteres' => array(
            'rule' => 'notEmpty'
        )
    );
	
	/**
 * hasMany associations
 *
 * @var array
 */
	
	var $hasMany  = array(
        'Producto' =>
            array('className' => 'Producto',
                 'foreignKey' => 'cliente_id',
            ),
		'Statu' =>
            array('className' => 'Statu',
                 'foreignKey' => 'cliente_id',
            )	
    );
	
}

?>