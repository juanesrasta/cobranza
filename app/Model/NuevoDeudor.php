<?php
App::uses('AppModel', 'Model');
class NuevoDeudor extends AppModel {
	 var $name = 'NuevoDeudor';
	 public $useTable = 'nuevos_deudores';

    public $belongsTo = array(
        'ClienProd' => array(
            'className'    => 'ClienProd',
            'foreignKey'   => 'clienProd_id'
        ),
    );
}

?>