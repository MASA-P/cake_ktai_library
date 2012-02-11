<?php
/**
 * Lib3gk, supports Japanese mobile phone sites coding.
 * It provides many functions such as a carrier check to use Referer or E-mail, 
 * conversion of an Emoji, and more.
 *
 * PHP versions 5
 *
 * Lib3gk
 * Copyright 2009-2012, ECWorks.
 
 * Licensed under The GNU General Public Licence
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2009-2012, ECWorks.
 * @link			http://www.ecworks.jp/ ECWorks.
 * @version			0.5.1
 * @lastmodified	$Date: 2012-02-12 06:00:00 +0900 (Sat, 12 Feb 2012) $
 * @license			http://www.gnu.org/licenses/gpl.html The GNU General Public Licence
 */
 
require_once 'PHPUnit/Framework.php';
require_once(dirname(dirname(__FILE__)).'/libs/lib3gk_machine.php');
require_once(dirname(__FILE__).'/includes/settings.php');

/**
 * Test class for Lib3gkMachine
 *
 * @package       Lib3gk
 * @subpackage    Lib3gk.tests
 */
class Lib3gkMachineTest extends PHPUnit_Framework_TestCase {

	protected $Lib3gkMachine = null;
	
	protected function setUp(){
		$this->Lib3gkMachine = new Lib3gkMachine();
		$this->Lib3gkMachine->initialize();
	}
	
	protected function tearDown(){
		$this->Lib3gkMachine->shutdown();
		$this->Lib3gkMachine = null;
		
	}
	
	public function testGetMachineInfo(){
		$carrier_name = 'others';
		$machine_name = 'default';
		$arr = $this->Lib3gkMachine->get_machineinfo($carrier_name, $machine_name);
		$this->assertTrue($arr['carrier_name'] == $carrier_name && $arr['machine_name'] == $machine_name);
		$this->assertFalse(isset($arr['font_size']));
		
		$carrier_name = 'Android';
		$machine_name = 'default';
		$arr = $this->Lib3gkMachine->get_machineinfo($carrier_name, $machine_name);
		$this->assertTrue($arr['carrier_name'] == $carrier_name && $arr['machine_name'] == $machine_name);
		
	}
	
}
