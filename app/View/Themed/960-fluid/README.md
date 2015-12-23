# CakePHP 960-fluid

A 960 based theme for CakePHP 2.1

##Installation


clone the repo to  
 * app/View/Themed/960-fluid directory


To switch the theme try this in your AppController




 class AppController extends Controller {

	public $viewClass = 'Theme';

		....



	function beforeFilter() {
		parent::beforeFilter();		
		$this->theme='960-fluid';		
		//special: View/Themed/960-fluid/Layouts/ admin.ctp and default.ctp
		//if you want to use the special admin layout, try this:
		$this->layout = 'admin';
	}
 } 
