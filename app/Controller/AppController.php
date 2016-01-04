<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

	public $uses = array('User','Permiso','Gestor');
	public $viewClass = 'Theme';
	// public $helpers = array('Menu');

	 public $components = array(
        'Session',
		'DebugKit.Toolbar',
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'index',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login'
            ),
            'authenticate' => array(
                'Form' => array(
                    'passwordHasher' => 'Blowfish'
                )
            ),
			'authorize' => array('Controller'),
			'unauthorizedRedirect' => '/'
        ),
    );
	
	public function isAuthorized($user) {
		
		// debug($this->request->params);
		$permisologia = $this->Permiso->findByNombre($user['rol']);
		// debug($permisologia);
		
		if($this->params['controller'] == 'users' && $this->params['action']== 'edit' && $this->params['pass'][0] == $user['id']){		return true;
		}
		if($this->params['controller'] == 'users' && $permisologia['Permiso']['usuarios'] == true){
			return true;
		}
		if($this->params['controller'] == 'clientes' && $permisologia['Permiso']['clientes'] == true){
			return true;
		}
		
		if($this->params['controller'] == 'productos' && $permisologia['Permiso']['productos'] == true){
			return true;
		}
		
		if($this->params['controller'] == 'permisos' && $permisologia['Permiso']['roles'] == true){
			return true;
		}
		
		if($this->params['controller'] == 'dias' && $permisologia['Permiso']['dias'] == true){
			return true;
		}
		
		if($this->params['controller'] == 'clientesProductos' && $permisologia['Permiso']['clientesProductos'] == true){
			return true;
		}
		
		if($this->params['controller'] == 'gruposProductos' && $permisologia['Permiso']['clientesProductos'] == true){
			return true;
		}
		
		if($this->params['controller'] == 'gestion' && $permisologia['Permiso']['gestion'] == true){
			return true;
		}
		
		if($this->params['controller'] == 'cartera' && $permisologia['Permiso']['cartera'] == true){
			return true;
		}
		
		if($this->params['controller'] == 'pagos' && $permisologia['Permiso']['pagos'] == true){
			return true;
		}
		
		if($this->params['controller'] == 'data' && $permisologia['Permiso']['pagos'] == true){
			return true;
		}

		// if (isset($user['rol']) && $user['rol'] === 'administrador') {
			// return true;
		// }

		// Default deny
		return false;
	}

    public function beforeFilter() {
	// debug('pass');
        // $this->Auth->allow('logout');
		// parent::beforeFilter();
		ini_set ('memory_limit','-1');
		$this->theme='960-fluid';
		$this->layout = 'admin';
		
		$username = $this->Auth->user('username');
		$rol_activo = $this->Auth->user('rol');
		$id = $this->Auth->user('id');
		
		if(!empty($username)){
			$this->set('username', $username);
			$this->set('id', $id);
			$this->set('rol_activo', $rol_activo);
		}
		
		//permisos
			$tipo_usuario = $this->Auth->user('rol');
			$permisos_usuarios = $this->Permiso->find('first', array(
				'conditions' => array(
					'nombre' => $tipo_usuario
				)
			));
			$this->set('permisos_usuarios', $permisos_usuarios);
			// debug($permisos_usuarios);
		//
    }
}
