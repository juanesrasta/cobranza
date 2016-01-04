<?php
// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

/**
 * Clientes Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */

class UsersController extends AppController {
	public $components = array('Paginator', 'Attempt.Attempt');
	public $uses = array('User','Role','Gestor');
	
	public $loginAttemptLimit = 3;
    public $loginAttemptDuration = '+1 hour';
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('logout', 'view');
	}
	
	function beforeSave() {
        if ($this->request->data['User']['pass']== ''){			
			unset($this->request->data['User']['password']);
        }else{
			$this->request->data['User']['password'] = $this->request->data['User']['pass'];
		}
		unset($this->request->data['User']['pass']);
     return true;         
 }

	public function login() {
		if ($this->request->is('post')) {
			if ( $this->Attempt->limit('login', $this->loginAttemptLimit) ) {
				if ($this->Auth->login()) {
					$this->Attempt->reset('login');
					if($this->Auth->user('status') == 'activo'){
						return $this->redirect($this->Auth->redirectUrl());
					}else{
						$this->Auth->logout();
						$this->Session->setFlash(__('Usuario inactivo'), 'error');
						return true;
					}
				}
			$this->Attempt->fail('login', $this->loginAttemptDuration);
			$this->Session->setFlash(__('Nombre de usuario o password incorrecto'), 'error');
			}else{
				$this->Session->setFlash(__('Usuario bloqueado, intente nuevamente en una hora.'), 'error');
			}
		}
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

    public function index() {
		$this->paginate = array(
			'recursive'=>0,
			'limit'=>10
		);
		$this->Paginator->settings['recursive'] = '2';
		$user = $this->paginate('User');
		foreach ($user as $u) {
			if ($u['User']['supervisor_id'] != 0) {
				$supervisor = $this->User->findById($u['User']['supervisor_id']);
				if ($supervisor['User']['status'] == 'activo') {
					$supervisores[$u['User']['id']] = $supervisor['User']['nombre_completo'];
				}
			}
		}
		$this->set(compact('user','supervisores'));
		
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuario incorrecto'), 'error');
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {
        if ($this->request->is('post')) {
			if ($this->request->data['User']['rol'] != 'operador') {
				$this->request->data['User']['supervisor_id'] = 0;
			} 
            $this->User->create();
            if ($this->User->save($this->request->data)) {
				if ($this->request->data['User']['rol'] == 'operador') {//Si el usuario es operador se guarda tambien en la tabla gestores
					$supervisa=0;
					if ($this->request->data['User']['status'] == 'activo') {
						$activo = 1;
					} else {
						$activo = 0;
					}
	$id = $this->User->query("SELECT User.id FROM users as User WHERE User.nombre_completo='".$this->request->data['User']['nombre_completo']."' ");
					$clave = $this->Gestor->crearClave($this->request->data['User']['nombre_completo'],$id[0]['User']['id']);
					$nuevo_gestor = array('Gestor' => array(
						'id'=>"",
						'Clave' => $clave,
						'Nombre' => $this->request->data['User']['nombre_completo'],
						'Comision'=>0,
						'supervisa'=>$supervisa,
						'Supervisor'=> $this->request->data['User']['supervisor_id'],
						'Activo' => $activo,
						'tipo'=>1,
						'Passwor'=>$this->request->data['User']['password'],
						'user_id' => $this->User->id,						
					));
					$this->Gestor->save($nuevo_gestor);
				}
                $this->Session->setFlash(__('El Usuario ha sido creado con éxito.'), 'success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('El Usuario no ha sido creado, intente nuevamente.'), 'error'
            );
        }
		$supervisors = $this->User->find('list',array(	
			'fields' => array('id','nombre_completo'),
			'conditions' => array('rol' => 'supervisor','status' => 'activo')
		));
		$this->set(compact('supervisors'));
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuario incorrecto'), 'error');
        }
		
		$this->User->validator()->remove('password');
        if ($this->request->is('post') || $this->request->is('put')) {
			if($this->request->data['User']['password'] = ''){
				unset($this->request->data['User']['password']);
			}
			$this->beforeSave();
			if ($this->request->data['User']['rol'] != 'operador') {
				$this->request->data['User']['supervisor_id'] = 0;
			} else { //Si el usuario es operador se guarda tambien en la tabla gestores
				$gestor = $this->Gestor->find('first',array('conditions'=>array('Gestor.user_id' => $this->request->data['User']['id'])));
				if ($this->request->data['User']['status'] == 'activo') {
					$activo = 1;
				} else {
					$activo = 0;
				}
				$clave = $this->Gestor->crearClave($this->request->data['User']['nombre_completo'],$this->request->data['User']['id']);
				if (!empty($gestor)) {
					$nuevo_gestor = array('Gestor' => array(
						'id' => $gestor['Gestor']['id'],
						'Clave' => $clave,
						'Nombre' => $this->request->data['User']['nombre_completo'],
						'Activo' => $activo,
						'user_id' => $this->request->data['User']['id'],
					));
				} else {
					$nuevo_gestor = array('Gestor' => array(
						'Clave' => $clave,
						'Nombre' => $this->request->data['User']['nombre_completo'],
						'Activo' => $activo,
						'user_id' => $this->request->data['User']['id'],
					));
				}
				$this->Gestor->save($nuevo_gestor);
			}
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Los datos han sido actualizados.'), 'success');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('Los cambios no se han guardado, intente nuevamente.'), 'error'
            );
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
		$supervisors = $this->User->find('list',array(	
			'fields' => array('id','nombre_completo'),
			'conditions' => array('rol' => 'supervisor','status' => 'activo')
		));
		$this->set(compact('supervisors','id'));
    }

    public function delete($id = null) {
        // Prior to 2.5 use
        // $this->request->onlyAllow('post');

        $this->request->allowMethod('get');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Usuario incorrecto'), 'error');
        } else {
			debug($this->User);die();
		}
		
        if ($this->User->delete()) {
            $this->Session->setFlash(__('Usuario Eliminado'), 'success');
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('El Usuario no ha podido ser eliminado'), 'error');
        return $this->redirect(array('action' => 'index'));
    }
	
	public function roles(){
		if ($this->request->is('post')){
			debug($this->data);
		}
		$roles = $this->Role->find('all');
		$this->set('roles', $roles);
	}
	
	public function edit_rol($rol_id = null){
		 $this->Role->id = $rol_id;
        if (!empty($this->data)) {
            if ($this->Role->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'roles'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->Role->read(null, $rol_id);
			debug('pass');
        }
	}
	
	
	/**
 * Activar a cliente 
 * @param int $id Id del usuario administrador que se va a activar
 * @return void
 */
	public function activar($id) {
		if ($this->request->is('post')) {
			if (!$this->User->exists($id)) {
				throw new NotFoundException(__('Usuario desconocido.'));
			}
			$user = $this->User->findById($id);
			$this->User->id = $id;
			if($user['User']['status']=='activo'){
				$this->User->saveField('status','inactivo');
				if ($user['User']['rol'] == 'supervisor') {
					$this->Session->setFlash(__('El Usuario ha sido desactivado.'));
					$this->redirect(array('action' => 'reasignar_operadores',$id));
				} else {
					$this->Session->setFlash(__('El Usuario ha sido desactivado.'));
				}
				
			}
			else{
				$this->User->saveField('status','activo');
				$this->Session->setFlash(__('El usuario ha sido activado.'));
			}
			if(isset($this->request->data['src'])&&$this->request->data['src']=='user')
				$this->redirect('user');
			else
				$this->redirect('index');
		}else
			$this->redirect($this->adminDefaultRoute);
	}


	//Reasignar operadores cuando se desactiva un operador
	function reasignar_operadores($supervisor_viejo_id = null) {
		if (!empty($this->data)) {
			foreach ($this->data['operador'] as $key => $o) {
				if ($o == 1) {
					$update_operador = array('User' => array(
						'id' => $key,
						'supervisor_id' => $this->data['User']['supervisor_id']
					));
					$this->User->save($update_operador);
				}
			}
		}
		$this->Paginator->settings['conditions'] = array('User.supervisor_id' => $supervisor_viejo_id);
		$operadores  = $this->Paginator->paginate('User');
		if (empty($operadores)) {
			$this->redirect(array('action' => 'index'));
		}
		$supervisors = $this->User->find('list',array(
			'fields' => array('id','nombre_completo'),
			'conditions' => array(
				'User.rol' => 'supervisor',
				'User.status' => 'activo'
			)
		));
		$this->set(compact('operadores','supervisors','supervisor_viejo_id'));
		
	}
}

?>
