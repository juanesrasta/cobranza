    <?php
// app/Controller/DiasController.php
App::uses('AppController', 'Controller');

class DiasController extends AppController {
	public $components = array('Paginator','Search.Prg');
	public $uses = array('Dia');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('logout', 'view');
	}
	
	public function index(){
		$this->Prg->commonProcess();
        $this->Paginator->settings['conditions'] = $this->Dia->parseCriteria($this->Prg->parsedParams());
		// debug($this->Paginator->settings['conditions']);
        $this->set('dias', $this->Paginator->paginate());
	}
	
	public function add() {
        if ($this->request->is('post')) {
            $this->Dia->create();
            if ($this->Dia->save($this->request->data)) {
                $this->Session->setFlash(__('Fecha Introducida con éxito!'), 'success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('La Fecha no ha sido creada, intente nuevamente.'), 'error'
            );
        }else{
			$fechas = $this->Dia->find('list', array(
				'fields' => array('Dia.fecha')
			));
			$this->set('fechas', $fechas);
		}
    }
	
	public function edit($id = null) {
		
        $this->Dia->id = $id;
        if (!$this->Dia->exists()) {
            $this->Session->setFlash(__('Fecha incorrecta'), 'error');
			
        }
		$this->Dia->validator()->remove('password');
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Dia->save($this->request->data)) {
                $this->Session->setFlash(__('Los datos han sido actualizados.'), 'success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('Los cambios no se han guardado, intente nuevamente.'), 'error'
            );
        }else{
			$this->request->data = $this->Dia->read(null, $id);
			$fechas = $this->Dia->find('list', array(
				'fields' => array('Dia.fecha')
			));
			$this->set('fechas', $fechas);
			// debug($fechas);
		}
    }
	
	public function delete($id = null) {

        $this->request->allowMethod('get');

        $this->Dia->id = $id;
        if (!$this->Dia->exists()) {
            throw new NotFoundException(__('Fecha incorrecta'), 'error');
        }
        if ($this->Dia->delete()) {
            $this->Session->setFlash(__('Fecha Eliminada'), 'success');
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('La Fecha no ha podido ser eliminada'), 'error');
        return $this->redirect(array('action' => 'index'));
    }
}

?>
