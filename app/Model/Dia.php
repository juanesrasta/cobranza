<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Dia extends AppModel {

	// public $hasOne = 'Role';
	
	public $actsAs = array(
		'Search.Searchable'
	);
	
	public $filterArgs = array(
		'descripcion' => array(
			'type' => 'like',
			'field' => 'descripcion'
		),
		'fecha' => array(
			'type' => 'query',
			'method' => 'filtro_fecha'
		),
    );
	
	public function filtro_fecha($data = array()){ // esto quedo bello! jajaja
		$fecha = explode('-',$data['fecha']);
		$month = $fecha[1];
		$day = $fecha[0];
		$year = $fecha[2];
		$condition = array();
		
		if(!empty($month)){
			$condition1 = array(
				'MONTH(fecha)' => $month
			);
			array_push($condition, $condition1);
		}
		
		if(!empty($day)){
			$condition2 = array(
					'DAY(fecha)' => $day
			);
			array_push($condition, $condition2);
		}
		
		if(!empty($year)){
			$condition3 = array(
					'YEAR(fecha)' => $year
			);
			array_push($condition, $condition3);
		}
		
		return $condition;
	}
	
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Es requerido un nombre de Usuario'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),
		'correo' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Por favor ingrese su correo electrónico'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'author')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        )
    );

    function array_meses(){
        $meses = array(
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
        );
        return $meses;
    }
}

?>