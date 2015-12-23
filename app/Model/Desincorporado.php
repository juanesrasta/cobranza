<?php
App::uses('AppModel', 'Model');

class Desincorporado extends AppModel {
    public $belongsTo = array(
        'ClienProd' => array(
            'className'    => 'ClienProd',
            'foreignKey'   => 'clien_prod_id'
        ),
    );
}
?>