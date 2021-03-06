<?php
/*
 * CakeKtaiLibrary, supports Japanese mobile phone sites coding.
 * This provides many functions such as a carrier check to use Referer or E-mail, 
 * conversion of an Emoji, and more.
 *
 * PHP versions 5
 *
 * CakeKtaiLibrary for CakePHP1.3
 * Copyright 2009-2012, ECWorks.
 
 * Licensed under The GNU General Public Licence
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2009-2012, ECWorks.
 * @link			http://www.ecworks.jp/ ECWorks.
 * @version			0.5.1
 * @lastmodified	$Date: 2012-02-13 03:00:00 +0900 (Mon, 13 Feb 2012) $
 * @license			http://www.gnu.org/licenses/gpl.html The GNU General Public Licence
 */

App::build(array(
	'controllers' => array(App::pluginPath('CakeKtaiLibrary').'tests'.DS.'test_app'.DS.'controllers'.DS), 
	'views' => array(App::pluginPath('CakeKtaiLibrary').'tests'.DS.'test_app'.DS.'views'.DS), 
));

App::import('Vendor', 'CakeKtaiLibrary.lib3gk/libs/Lib3gkCarrier');
App::import('Controller', 'KtaiTests');

class TestKtaiTestsController extends CakeTestCase {
	
	var $carrier   = null;
	var $controller = null;
	
	function start(){
		$this->carrier = Lib3gkCarrier::get_instance();
		$this->carrier->_carrier = KTAI_CARRIER_DOCOMO;
		
		Router::reload();
		$this->controller =& new KtaiTestsController();
		$this->controller->constructClasses();
		$this->controller->Component->initialize($this->controller);
		$this->controller->Component->startup($this->controller);
	}
	
	function stop(){
		unset($this->controller);
		ClassRegistry::flush();
	}
	
	function testIndex(){
		$url = array(
			'controller' => 'ktai_tests', 
			'action' => 'index', 
		);
		
		$test_url = $this->controller->__redirect_url($url);
		$result = is_array($test_url) && 
			$test_url['controller'] == 'ktai_tests' && 
			$test_url['action'] == 'index' && 
			isset($test_url['?']['csid']);
		$this->assertTrue($result);
		
		$url['?'] = array('testvalue' => 5);
		$test_url = $this->controller->__redirect_url($url);
		$result = is_array($test_url) && 
			$test_url['controller'] == 'ktai_tests' && 
			$test_url['action'] == 'index' && 
			isset($test_url['?']['csid']) && 
			$test_url['?']['testvalue'] == 5;
		$this->assertTrue($result);
		
		$url = '/ktai_tests/index/5';
		$test_url = $this->controller->__redirect_url($url);
		$result = is_array($test_url) && 
			$test_url['controller'] == 'ktai_tests' && 
			$test_url['action'] == 'index' && 
			isset($test_url['?']['csid']) && 
			$test_url['pass'][0] == 5;
		$this->assertTrue($result);
		
		$url = 'http://www.google.com/';
		$test_url = $this->controller->__redirect_url($url);
		$this->assertEqual($test_url, $url);
		
	}
	
}
