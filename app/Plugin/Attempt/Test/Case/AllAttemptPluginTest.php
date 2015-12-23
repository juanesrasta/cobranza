<?php
class AllAttemptPluginTest extends CakeTestSuite {
/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All Attempt Plugin Tests');

		$basePath = CakePlugin::path('Attempt') . 'Test' . DS . 'Case' . DS;
		// controllers
		$suite->addTestFile($basePath . 'Controller' . DS . 'Component' . DS . 'AttemptComponentTest.php');
		// models
		$suite->addTestFile($basePath . 'Model' . DS . 'AttemptTest.php');

		return $suite;
	}

}