<?php
/**
 * Ktai library, supports Japanese mobile phone sites coding.
 * It provides many functions such as a carrier check to use Referer or E-mail, 
 * conversion of an Emoji, and more.
 *
 * PHP versions 5
 *
 * Ktai Library for CakePHP
 * Copyright 2009-2011, ECWorks.
 
 * Licensed under The GNU General Public Licence
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2009-2011, ECWorks.
 * @link			http://www.ecworks.jp/ ECWorks.
 * @version			0.5.0
 * @lastmodified	$Date: 2011-04-10 03:00:00 +0900 (Sun, 10 Apl 2011) $
 * @license			http://www.gnu.org/licenses/gpl.html The GNU General Public Licence
 */
 
require_once 'PHPUnit/Framework.php';
require_once(dirname(dirname(__FILE__)).'/libs/lib3gk_html.php');
require_once(dirname(dirname(__FILE__)).'/libs/lib3gk_carrier.php');
require_once(dirname(__FILE__).'/includes/settings.php');

/**
 * Test class for Lib3gkHtml
 *
 * @package       Lib3gk
 * @subpackage    Lib3gk.tests
 */
class Lib3gkHtmlTest extends PHPUnit_Framework_TestCase {

	protected $Lib3gk = null;
	
	protected function setUp(){
		$this->Lib3gkHtml = new Lib3gkHtml();
		$this->Lib3gkHtml->initialize();
	}
	
	protected function tearDown(){
		$this->Lib3gkHtml->shutdown();
		$this->Lib3gkHtml = null;
	}
	
	public function testUrl(){
		$str = './" /><script language="javascript">alert("test");</script><img src="./';
		$check = htmlspecialchars($str);
		$result = $this->Lib3gkHtml->url($str);
		$this->assertEquals($check, $result);
	}
	
	public function testImage(){
	
		$str = './img/cake.icon.png';
		$check = $str;
		$result = $this->Lib3gkHtml->image($str, array('width' => 20, 'height' => 20));
		$check = '<img src="./img/cake.icon.png" width="20" height="20">';
		$this->assertEquals($check, $result);
		
		$carrier = Lib3gkCarrier::get_instance();
		$carrier->get_carrier('SoftBank/1.0/940SH/SHJ001[/Serial] Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1', true);
		$check = '<img src="./img/cake.icon.png" width="40" height="40">';
		$result = $this->Lib3gkHtml->image($str, array('width' => 20, 'height' => 20));
		$this->assertEquals($check, $result);
	}
	
	public function testStretchImageSize(){
	
		$carrier = Lib3gkCarrier::get_instance();
		
		$width  = 20;
		$height = 40;
		$default_width  = 240;
		$default_height = 320;
		
		$carrier->get_carrier('', true);
		list($result_width, $result_height) = $this->Lib3gkHtml->stretch_image_size($width, $height, $default_width, $default_height);
		$this->assertEquals($result_width,  20);
		$this->assertEquals($result_height, 40);
		
		$carrier->get_carrier('SoftBank/1.0/940SH/SHJ001[/Serial] Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1', true);
		list($result_width, $result_height) = $this->Lib3gkHtml->stretch_image_size($width, $height, $default_width, $default_height);
		$this->assertEquals($result_width,  40);
		$this->assertEquals($result_height, 80);
	}
	public function testStyle(){
		$style = 'color: #ffffff;';
		$this->Lib3gkHtml->_params['style']['test'] = $style;
		$result = $this->Lib3gkHtml->style('test', false);
		$this->assertEquals($result, $style);
	}
	
	public function testGetQrcode(){
		$str = 'Ktai Library';
		$result = $this->Lib3gkHtml->get_qrcode($str);
		$this->assertTrue(preg_match('/Ktai Library/', $result) !== false);
	}
	
	public function testGetStaticMaps(){
		
		$carrier = Lib3gkCarrier::get_instance();
		$carrier->get_carrier('', true);
		
		$lat = '-12.3456';
		$lon = '12.3456';
		$options = array(
			'markers' => array(
				array('-12.3456', '12.3456', 'mid', 'red', '1'), 
				array('-34.5678', '34.5678', 'tiny', 'blue', 'a'), 
				array('-56.7890', '56.7890', 'green', null), 
			), 
			'path' => array(
				'rgb'    => '0xff0000', 
				'weight' => '1', 
				'points' => array(
					array('-12.3456', '12.3456'), 
					array('-34.5678', '34.5678'), 
					array('-56.7890', '56.7890'), 
				), 
			), 
			'span' => array(100, 100), 
		);
		$this->Lib3gkHtml->_params['google_api_key'] = '0123456789';
		$result = $this->Lib3gkHtml->get_static_maps($lat, $lon, $options);
	}
	
	public function testFont(){
		$carrier = Lib3gkCarrier::get_instance();
		$carrier->get_carrier('', true);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals(null, $result);
		
		$result = $this->Lib3gkHtml->font();
		$this->assertEquals(null, $result);
		
		$user_agent = 'DoCoMo/2.0 P906i(c100;TB;W24H15)';
		$carrier->get_carrier($user_agent, true);
		$this->Lib3gkHtml->_params['use_xml'] = false;
		$this->Lib3gkHtml->_params['style'] = array(
			'teststyle' => 'color: red;', 
		);
		$result = $this->Lib3gkHtml->font();
		$this->assertEquals(null, $result);
		
		$result = $this->Lib3gkHtml->_params['use_xml'] = true;
		$result = $this->Lib3gkHtml->font();
		$this->assertEquals('<div style="font-size: medium;">', $result);
		
		$result = $this->Lib3gkHtml->font(null, 'div');
		$this->assertEquals('<div style="font-size: medium;">', $result);
		
		$result = $this->Lib3gkHtml->font(null, null, 'teststyle');
		$this->assertEquals('<div style="font-size: medium;color: red;">', $result);
		
		$user_agent = 'KDDI-KC3Z UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0';
		$carrier->get_carrier($user_agent, true);
		$result = $this->Lib3gkHtml->font();
		$this->assertEquals('<font style="font-size: 22px;">', $result);
		
		$result = $this->Lib3gkHtml->font(null, 'div');
		$this->assertEquals('<div style="font-size: 22px;">', $result);
		
		$result = $this->Lib3gkHtml->font(null, null, 'teststyle');
		$this->assertEquals('<font style="font-size: 22px;color: red;">', $result);
		
		$user_agent = 'SoftBank/1.0/840SH/SHJ001/0123456789 Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1';
		$carrier->get_carrier($user_agent, true);
		$result = $this->Lib3gkHtml->font();
		$this->assertEquals('<font style="font-size: medium;">', $result);
		
		$result = $this->Lib3gkHtml->font(null, 'div');
		$this->assertEquals('<div style="font-size: medium;">', $result);
		
		$result = $this->Lib3gkHtml->font(null, null, 'teststyle');
		$this->assertEquals('<font style="font-size: medium;color: red;">', $result);
		
		$user_agent = 'SoftBank/2.0/945SH/SHJ001/0123456789 Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1';
		$carrier->get_carrier($user_agent, true);
		$result = $this->Lib3gkHtml->font();
		$this->assertEquals('<font style="font-size: large;">', $result);
		
		$result = $this->Lib3gkHtml->font(null, 'div');
		$this->assertEquals('<div style="font-size: large;">', $result);
		
		$result = $this->Lib3gkHtml->font(null, null, 'teststyle');
		$this->assertEquals('<font style="font-size: large;color: red;">', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</font>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</div>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</font>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</font>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</div>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</font>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</font>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</div>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</font>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</div>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</div>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals('</div>', $result);
		
		$result = $this->Lib3gkHtml->fontend();
		$this->assertEquals(null, $result);
		
	}
	
}
