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
require_once(dirname(dirname(__FILE__)).'/libs/lib3gk.php');
require_once(dirname(__FILE__).'/includes/settings.php');

/**
 * Test class for Lib3gk
 *
 * @package       Lib3gk
 * @subpackage    Lib3gk.tests
 */
class Lib3gkTest extends PHPUnit_Framework_TestCase {

	protected $Lib3gk = null;
	
	protected function setUp(){
		$this->Lib3gk = new Lib3gk();
		$this->Lib3gk->initialize();
	}
	
	protected function tearDown(){
		$this->Lib3gk->shutdown();
		$this->Lib3gk = null;
		
	}
	
	public function testGetVersion(){
		$str = $this->Lib3gk->get_version();
		$this->assertEquals($str, '0.5.1');
	}
	
	public function testGetIpCarrier(){
		$result = $this->Lib3gk->get_ip_carrier();
		$this->assertTrue(is_integer($result));
	}
	
}
