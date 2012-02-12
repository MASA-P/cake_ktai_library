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
require_once(dirname(dirname(__FILE__)).'/libs/lib3gk_tools.php');
require_once(dirname(dirname(__FILE__)).'/libs/lib3gk_carrier.php');
require_once(dirname(__FILE__).'/includes/settings.php');

/**
 * Test class for Lib3gkTools
 *
 * @package       Lib3gk
 * @subpackage    Lib3gk.tests
 */
class Lib3gkToolsTest extends PHPUnit_Framework_TestCase {

	protected $Lib3gkTools = null;
	
	protected function setUp(){
		
		$this->Lib3gkTools = new Lib3gkTools();
		$this->Lib3gkTools->initialize();
	}
	
	protected function tearDown(){
		$this->Lib3gkTools->shutdown();
		$this->Lib3gkTools = null;
		
	}
	
	public function testInt2Str(){
		$str = $this->Lib3gkTools->int2str(0x82a0);
		$this->assertEquals($str, mb_convert_encoding('あ', 'SJIS', 'UTF-8'));
	}
	
	public function testInt2Utf8(){
		$str = $this->Lib3gkTools->int2utf8(0x3042);
		$this->assertEquals($str, 'あ');
	}
	
	public function testStr2Int(){
		$str = $this->Lib3gkTools->str2int(mb_convert_encoding('あ', 'SJIS', 'UTF-8'));
		$this->assertEquals($str, 0x82a0);
		$str = $this->Lib3gkTools->str2int('A');
		$this->assertEquals($str, false);
		$str = $this->Lib3gkTools->str2int(mb_convert_encoding('あi', 'SJIS', 'UTF-8'));
		$this->assertEquals($str, false);
		$str = $this->Lib3gkTools->str2int('あ');
		$this->assertEquals($str, false);
	}
	
	public function testUtf82Int(){
		$str = $this->Lib3gkTools->utf82int('あ');
		$this->assertEquals($str, 0x3042);
		$str = $this->Lib3gkTools->utf82int('A');
		$this->assertEquals($str, false);
		$str = $this->Lib3gkTools->utf82int('あi');
		$this->assertEquals($str, false);
		$str = $this->Lib3gkTools->utf82int(mb_convert_encoding('あ', 'SJIS', 'UTF-8'));
		$this->assertEquals($str, false);
	}
	
	public function testNormalEncodingStr(){
		$str = $this->Lib3gkTools->normal_encoding_str('Shift_JIS');
		$this->assertEquals($str, 'SJIS');
	}
	
	public function testMailto(){
		
		$carrier = Lib3gkCarrier::get_instance();
		
		$title   = 'テストメール';
		$to      = 'test@example.com';
		$subject = 'testmailです';
		$body    = "テストmailです\r\nKtai Libraryからメールを送信しています";
		
		//testing for PC, docomo, kddi, emobile, iPhone
		//
		$s = urlencode(mb_convert_encoding($subject, 'SJIS', 'UTF-8'));
		$b = urlencode(mb_convert_encoding($body, 'SJIS', 'UTF-8'));
		$expected = '<a href="mailto:'.$to.'?subject='.$s.'&body='.$b.'">'.$title.'</a>';
		
		$carrier->_carrier = KTAI_CARRIER_UNKNOWN;
		$str = $this->Lib3gkTools->mailto($title, $to, $subject, $body, null, null, false);
		$this->assertEquals($expected, $str);
		
		$carrier->_carrier = KTAI_CARRIER_DOCOMO;
		$str = $this->Lib3gkTools->mailto($title, $to, $subject, $body, null, null, false);
		$this->assertEquals($expected, $str);
		
		$carrier->_carrier = KTAI_CARRIER_KDDI;
		$str = $this->Lib3gkTools->mailto($title, $to, $subject, $body, null, null, false);
		$this->assertEquals($expected, $str);
		
		$carrier->_carrier = KTAI_CARRIER_EMOBILE;
		$str = $this->Lib3gkTools->mailto($title, $to, $subject, $body, null, null, false);
		$this->assertEquals($expected, $str);
		
		//testing for SoftBank, iPhone
		//
		$s = urlencode($subject);
		$b = urlencode($body);
		$expected = '<a href="mailto:'.$to.'?subject='.$s.'&body='.$b.'">'.$title.'</a>';
		
		$carrier->_carrier = KTAI_CARRIER_SOFTBANK;
		$str = $this->Lib3gkTools->mailto($title, $to, $subject, $body, null, null, false);
		$this->assertEquals($expected, $str);
		
		//testing for iPhone
		//
		$s = $subject;
		$b = mb_ereg_replace("\n", "%0D%0A", mb_ereg_replace("\r", "", $body));
		$expected = '<a href="mailto:'.$to.'?subject='.$s.'&body='.$b.'">'.$title.'</a>';
		
		$carrier->_carrier = KTAI_CARRIER_IPHONE;
		$str = $this->Lib3gkTools->mailto($title, $to, $subject, $body, null, null, false);
		$this->assertEquals($expected, $str);
		
	}
	
	public function testGetUid(){
		
		$carrier = Lib3gkCarrier::get_instance();
		
		//#28 softbank jphoneの端末ID取得時にエラー
		//
		$carrier->_carrier = KTAI_CARRIER_SOFTBANK;
		$str = $this->Lib3gkTools->get_uid();
		$this->assertFalse($str);		//PCでは必ずfalseになるのでエラーが出ないことだけを確認
	}
	
}
