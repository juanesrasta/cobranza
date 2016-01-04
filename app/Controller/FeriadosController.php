<?php
App::uses('AppController', 'Controller');

class FeriadosController extends AppController {
	public $components = array('Paginator');
	public $uses = array('Feriado');

    function index() {
       //$this->paginate = array( 'recursive'=>0, 'limit'=>10 );
		//$this->set('feriados', $this->paginate('Feriado'));
	   $this->set('feriados', $this->Feriado->find('all'));
    }
	
	public function view($fecha = null) {
        $this->Feriado->Fecha = $fecha;
        $this->set('feriados', $this->Feriado->read());
    }
}


?>
