<?php

App::uses('AppController', 'Controller');

class PermisosController extends AppController {
	
	public $uses = array('Permiso');
	// public function beforeFilter() {
	// parent::beforeFilter();
		// Allow users to register and logout.
		// $this->Auth->allow('add', 'logout');
	// }
	
	  public function index() {
	
        $this->Permiso->recursive = 0;
        $this->set('permisos', $this->paginate());
    }

    public function edit($id = null) {
	
        $this->Permiso->id = $id;
        if (!$this->Permiso->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Permiso->save($this->request->data)) {
                $this->Session->setFlash(__('Los permisos han sido cambiados'), 'success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('No se han podido cambiar los permisos, intente nuevamente.'), 'error'
            );
        } else {
            $this->request->data = $this->Permiso->read(null, $id);
            // unset($this->request->data['Role']['password']);
        }
    }

    public function delete($id = null) {
        // Prior to 2.5 use
        // $this->request->onlyAllow('post');

        $this->request->allowMethod('get');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('Usuario Eliminado'), 'success');
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('El Usuario no ha podido ser eliminado'), 'error');
        return $this->redirect(array('action' => 'index'));
    }

}

?>
