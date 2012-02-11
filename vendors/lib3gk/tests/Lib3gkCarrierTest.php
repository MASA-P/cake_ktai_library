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
require_once(dirname(dirname(__FILE__)).'/libs/lib3gk_carrier.php');
require_once(dirname(__FILE__).'/includes/settings.php');

/**
 * Test class for Lib3gkCarrier
 *
 * @package       Lib3gk
 * @subpackage    Lib3gk.tests
 */
class Lib3gkCarrierTest extends PHPUnit_Framework_TestCase {

	protected $Lib3gkCarrier = null;
	
	protected function setUp(){
		$this->Lib3gkCarrier = new Lib3gkCarrier();
		$this->Lib3gkCarrier->initialize();
	}
	
	protected function tearDown(){
		$this->Lib3gkCarrier->shutdown();
		$this->Lib3gkCarrier = null;
		
	}
	
	public function testAnalyzeUserAgent(){
		$arr = $this->Lib3gkCarrier->analyze_user_agent();
		$this->assertTrue(is_array($arr));
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_UNKNOWN);
		
		$user_agent = 'DoCoMo/1.0/SO506iS/c20/TB/W20H10';
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_DOCOMO);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_DOCOMO;
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_DOCOMO);
		$this->assertEquals($arr['machine_name'], 'P906i');
		
		//#1 一部端末の機種判別が出来ない
		$user_agent = 'DoCoMo/2.0 SO902iWP+(c100;TB;W24H12)';
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_DOCOMO);
		$this->assertEquals($arr['machine_name'], 'SO902iWP+');
		
		$user_agent = LIB3GK_TEST_USER_AGENT_KDDI;
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_KDDI);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_SOFTBANK;
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_SOFTBANK);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_EMOBILE;
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_EMOBILE);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_IPHONE;
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_IPHONE);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_PHS;
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_PHS);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_ANDROID;
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_ANDROID);
		
		$user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KDDI-TS01; Windows Phone 6.5.3.5)';
		$arr = $this->Lib3gkCarrier->analyze_user_agent($user_agent);
		$this->assertEquals($arr['carrier'], KTAI_CARRIER_KDDI);
		$this->assertEquals($arr['machine_name'], 'default');
		
	}
	
	public function testGetCarrier(){
		$test_value = $this->Lib3gkCarrier->get_carrier();
		$this->assertEquals($test_value, KTAI_CARRIER_UNKNOWN);
		$this->assertTrue($this->Lib3gkCarrier->_carrier == KTAI_CARRIER_UNKNOWN && $this->Lib3gkCarrier->_carrier_name == 'others' && $this->Lib3gkCarrier->_machine_name == 'default');
		
		$user_agent = LIB3GK_TEST_USER_AGENT_DOCOMO;
		$test_value = $this->Lib3gkCarrier->get_carrier($user_agent);
		$this->assertEquals($test_value, KTAI_CARRIER_DOCOMO);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_KDDI;
		$test_value = $this->Lib3gkCarrier->get_carrier($user_agent);
		$this->assertEquals($test_value, KTAI_CARRIER_KDDI);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_SOFTBANK;
		$test_value = $this->Lib3gkCarrier->get_carrier($user_agent);
		$this->assertEquals($test_value, KTAI_CARRIER_SOFTBANK);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_EMOBILE;
		$test_value = $this->Lib3gkCarrier->get_carrier($user_agent);
		$this->assertEquals($test_value, KTAI_CARRIER_EMOBILE);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_IPHONE;
		$test_value = $this->Lib3gkCarrier->get_carrier($user_agent);
		$this->assertEquals($test_value, KTAI_CARRIER_IPHONE);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_PHS;;
		$test_value = $this->Lib3gkCarrier->get_carrier($user_agent);
		$this->assertEquals($test_value, KTAI_CARRIER_PHS);
		
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_DOCOMO;
		$test_value = $this->Lib3gkCarrier->get_carrier();
		$this->assertEquals($test_value, KTAI_CARRIER_DOCOMO);
		
		$test_value = $this->Lib3gkCarrier->get_carrier(null, true);
		$this->assertEquals($test_value, KTAI_CARRIER_UNKNOWN);
		
		$user_agent = LIB3GK_TEST_USER_AGENT_ANDROID;
		$test_value = $this->Lib3gkCarrier->get_carrier($user_agent);
		$this->assertEquals($test_value, KTAI_CARRIER_ANDROID);
		
		$user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; KDDI-TS01; Windows Phone 6.5.3.5)';
		$test_value = $this->Lib3gkCarrier->get_carrier($user_agent);
		$this->assertEquals($test_value, KTAI_CARRIER_KDDI);
		
	}
	
	public function testIsImode(){
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_DOCOMO;
		$test_value = $this->Lib3gkCarrier->is_imode();
		$this->assertTrue($test_value);
	}
	
	public function testIsSoftbank(){
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_SOFTBANK;
		$test_value = $this->Lib3gkCarrier->is_softbank();
		$this->assertTrue($test_value);
		
		$this->Lib3gkCarrier->_params['iphone_user_agent_belongs_to_softbank'] = true;
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_IPHONE;
		$test_value = $this->Lib3gkCarrier->is_softbank();
	}
	
	public function testIsEzweb(){
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_KDDI;
		$test_value = $this->Lib3gkCarrier->is_ezweb();
		$this->assertTrue($test_value);
	}
	
	public function testIsEmobile(){
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_EMOBILE;
		$test_value = $this->Lib3gkCarrier->is_emobile();
		$this->assertTrue($test_value);
	}
	
	public function testIsIphone(){
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_IPHONE;
		$test_value = $this->Lib3gkCarrier->is_iphone();
		$this->assertTrue($test_value);
	}
	
	public function testIsAndroid(){
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_ANDROID;
		$test_value = $this->Lib3gkCarrier->is_android();
		$this->assertTrue($test_value);
	}
	
	public function testIsKtai(){
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_DOCOMO;
		$test_value = $this->Lib3gkCarrier->is_ktai();
		$this->assertTrue($test_value);
		
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_SOFTBANK;
		$test_value = $this->Lib3gkCarrier->is_ktai();
		$this->assertTrue($test_value);
		
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_KDDI;
		$test_value = $this->Lib3gkCarrier->is_ktai();
		$this->assertTrue($test_value);
		
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_EMOBILE;
		$test_value = $this->Lib3gkCarrier->is_ktai();
		$this->assertTrue($test_value);
		
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_UNKNOWN;
		$test_value = $this->Lib3gkCarrier->is_ktai();
		$this->assertFalse($test_value);
		
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_IPHONE;
		$this->Lib3gkCarrier->_params['iphone_user_agent_belongs_to_softbank'] = false;
		$this->Lib3gkCarrier->_params['iphone_user_agent_belongs_to_ktai'] = false;
		$test_value = $this->Lib3gkCarrier->is_ktai();
		$this->assertFalse($test_value);
		$this->Lib3gkCarrier->_params['iphone_user_agent_belongs_to_ktai'] = true;
		$test_value = $this->Lib3gkCarrier->is_ktai();
		$this->assertTrue($test_value);
		
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_ANDROID;
		$this->Lib3gkCarrier->_params['android_user_agent_belongs_to_ktai'] = false;
		$test_value = $this->Lib3gkCarrier->is_ktai();
		$this->assertFalse($test_value);
		$this->Lib3gkCarrier->_params['android_user_agent_belongs_to_ktai'] = true;
		$test_value = $this->Lib3gkCarrier->is_ktai();
		$this->assertTrue($test_value);
	}
	
	public function testIsPhs(){
		$this->Lib3gkCarrier->_carrier = KTAI_CARRIER_PHS;
		$test_value = $this->Lib3gkCarrier->is_phs();
		$this->assertTrue($test_value);
	}
	
	public function testIsImodeEmail(){
		$mail = 'test@docomo.ne.jp';
		$test_value = $this->Lib3gkCarrier->is_imode_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@example.com';
		$test_value = $this->Lib3gkCarrier->is_imode_email($mail);
		$this->assertFalse($test_value);
	}
	
	public function testIsSoftbankEmail(){
		$mail = 'test@softbank.ne.jp';
		$test_value = $this->Lib3gkCarrier->is_softbank_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@i.softbank.jp';
		$this->Lib3gkCarrier->_params['iphone_email_belongs_to_softbank_email'] = false;
		$test_value = $this->Lib3gkCarrier->is_softbank_email($mail);
		$this->assertFalse($test_value);
		$this->Lib3gkCarrier->_params['iphone_email_belongs_to_softbank_email'] = true;
		$test_value = $this->Lib3gkCarrier->is_softbank_email($mail);
		$this->assertTrue($test_value);
		
		
		$mail = 'test@example.com';
		$test_value = $this->Lib3gkCarrier->is_softbank_email($mail);
		$this->assertFalse($test_value);
	}
	
	public function testIsIphoneEmail(){
		$mail = 'test@i.softbank.jp';
		$test_value = $this->Lib3gkCarrier->is_iphone_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@example.com';
		$test_value = $this->Lib3gkCarrier->is_iphone_email($mail);
		$this->assertFalse($test_value);
	}
	
	public function testIsEzwebEmail(){
		$mail = 'test@ezweb.ne.jp';
		$test_value = $this->Lib3gkCarrier->is_ezweb_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@example.com';
		$test_value = $this->Lib3gkCarrier->is_ezweb_email($mail);
		$this->assertFalse($test_value);
	}
	
	public function testIsEmobileEmail(){
		$mail = 'test@emnet.ne.jp';
		$test_value = $this->Lib3gkCarrier->is_emobile_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@example.com';
		$test_value = $this->Lib3gkCarrier->is_emobile_email($mail);
		$this->assertFalse($test_value);
	}
	
	public function testIsKtaiEmail(){
		$mail = 'test@docomo.ne.jp';
		$test_value = $this->Lib3gkCarrier->is_ktai_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@ezweb.ne.jp';
		$test_value = $this->Lib3gkCarrier->is_ktai_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@softbank.ne.jp';
		$test_value = $this->Lib3gkCarrier->is_ktai_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@emnet.ne.jp';
		$test_value = $this->Lib3gkCarrier->is_ktai_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@i.softbank.jp';
		$this->Lib3gkCarrier->_params['iphone_email_belongs_to_softbank_email'] = false;
		$this->Lib3gkCarrier->_params['iphone_email_belongs_to_ktai_email'] = false;
		$test_value = $this->Lib3gkCarrier->is_ktai_email($mail);
		$this->assertFalse($test_value);
		$this->Lib3gkCarrier->_params['iphone_email_belongs_to_ktai_email'] = true;
		$test_value = $this->Lib3gkCarrier->is_ktai_email($mail);
		$this->assertTrue($test_value);
		
		$this->Lib3gkCarrier->_params['iphone_email_belongs_to_softbank_email'] = true;
		$this->Lib3gkCarrier->_params['iphone_email_belongs_to_ktai_email'] = false;
		$test_value = $this->Lib3gkCarrier->is_ktai_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@example.com';
		$test_value = $this->Lib3gkCarrier->is_ktai_email($mail);
		$this->assertFalse($test_value);
	}
	
	public function testIsPhsEmail(){
		$mail = 'test@willcom.com';
		$test_value = $this->Lib3gkCarrier->is_phs_email($mail);
		$this->assertTrue($test_value);
		
		$mail = 'test@example.com';
		$test_value = $this->Lib3gkCarrier->is_phs_email($mail);
		$this->assertFalse($test_value);
	}
	
	public function testGetEmailCarrier(){
		$mail = 'test@example.com';
		$test_value = $this->Lib3gkCarrier->get_email_carrier($mail);
		$this->assertEquals($test_value, KTAI_CARRIER_UNKNOWN);
		
		$mail = 'test@docomo.ne.jp';
		$test_value = $this->Lib3gkCarrier->get_email_carrier($mail);
		$this->assertEquals($test_value, KTAI_CARRIER_DOCOMO);
		
		$mail = 'test@ezweb.ne.jp';
		$test_value = $this->Lib3gkCarrier->get_email_carrier($mail);
		$this->assertEquals($test_value, KTAI_CARRIER_KDDI);
		
		$mail = 'test@softbank.ne.jp';
		$test_value = $this->Lib3gkCarrier->get_email_carrier($mail);
		$this->assertEquals($test_value, KTAI_CARRIER_SOFTBANK);
		
		$mail = 'test@emnet.ne.jp';
		$test_value = $this->Lib3gkCarrier->get_email_carrier($mail);
		$this->assertEquals($test_value, KTAI_CARRIER_EMOBILE);
		
		$mail = 'test@i.softbank.jp';
		$test_value = $this->Lib3gkCarrier->get_email_carrier($mail);
		$this->assertEquals($test_value, KTAI_CARRIER_IPHONE);
		
		$mail = 'test@willcom.com';
		$test_value = $this->Lib3gkCarrier->get_email_carrier($mail);
		$this->assertEquals($test_value, KTAI_CARRIER_PHS);
	}
	
}
