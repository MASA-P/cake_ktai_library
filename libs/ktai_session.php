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
 * @lastmodified	$Date: 2012-02-12 03:00:00 +0900 (Sun, 12 Feb 2012) $
 * @license			http://www.gnu.org/licenses/gpl.html The GNU General Public Licence
 */

//Replacement function ini_set('session.use_trans_sid')
//
if(!defined('__KTAI_SESSION__')){
	define('__KTAI_SESSION__', 1);
	function session_use_trans_sid($flag){
		if(ini_set('session.use_trans_sid', $flag) === false){
			if($flag){
				$session_name = session_name();
				if(isset($_REQUEST[$session_name]) && preg_match('/^\w+$/', $_REQUEST[$session_name])){
					session_id($_REQUEST[$session_name]);
					output_add_rewrite_var($session_name, $_REQUEST[$session_name]);
				}
			}
		}
	}
}

//Get Lib3gk instance.
//
if(!class_exists('lib3gk')){
	App::import('Vendor', 'ecw'.DS.'Lib3gk');
}
$ktai = Lib3gk::get_instance();

if(!isset($ktai->_params['session_save'])){
	$ktai->_params['session_save'] = 'php';
}
Configure::write('Session.save', $ktai->_params['session_save']);

if(!isset($ktai->_params['imode_session_name'])){
	$ktai->_params['imode_session_name'] = 'csid';
}
if($ktai->is_imode()){
	Configure::write('Session.cookie', $ktai->_params['imode_session_name']);
}

//Session.save proccess for CakePHP1.3(implements from 1.3.5)
//
switch (Configure::read('Session.save')) {
	case 'cake':
		if (empty($_SESSION)) {
			if ($iniSet) {
				ini_set('session.use_trans_sid', 0);
				ini_set('url_rewriter.tags', '');
				ini_set('session.serialize_handler', 'php');
				ini_set('session.use_cookies', 1);
				ini_set('session.name', Configure::read('Session.cookie'));
				ini_set('session.cookie_lifetime', $this->cookieLifeTime);
				ini_set('session.cookie_path', $this->path);
				ini_set('session.auto_start', 0);
				ini_set('session.save_path', TMP . 'sessions');
			}
		}
	break;
	case 'database':
		if (empty($_SESSION)) {
			if (Configure::read('Session.model') === null) {
				trigger_error(__("You must set the all Configure::write('Session.*') in core.php to use database storage"), E_USER_WARNING);
				$this->_stop();
			}
			if ($iniSet) {
				ini_set('session.use_trans_sid', 0);
				ini_set('url_rewriter.tags', '');
				ini_set('session.save_handler', 'user');
				ini_set('session.serialize_handler', 'php');
				ini_set('session.use_cookies', 1);
				ini_set('session.name', Configure::read('Session.cookie'));
				ini_set('session.cookie_lifetime', $this->cookieLifeTime);
				ini_set('session.cookie_path', $this->path);
				ini_set('session.auto_start', 0);
			}
		}
		session_set_save_handler(
			array('CakeSession','__open'),
			array('CakeSession', '__close'),
			array('CakeSession', '__read'),
			array('CakeSession', '__write'),
			array('CakeSession', '__destroy'),
			array('CakeSession', '__gc')
		);
	break;
	case 'php':
		if (empty($_SESSION)) {
			if ($iniSet) {
				ini_set('session.use_trans_sid', 0);
				ini_set('session.name', Configure::read('Session.cookie'));
				ini_set('session.cookie_lifetime', $this->cookieLifeTime);
				ini_set('session.cookie_path', $this->path);
			}
		}
	break;
	case 'cache':
		if (empty($_SESSION)) {
			if (!class_exists('Cache')) {
				require LIBS . 'cache.php';
			}
			if ($iniSet) {
				ini_set('session.use_trans_sid', 0);
				ini_set('url_rewriter.tags', '');
				ini_set('session.save_handler', 'user');
				ini_set('session.use_cookies', 1);
				ini_set('session.name', Configure::read('Session.cookie'));
				ini_set('session.cookie_lifetime', $this->cookieLifeTime);
				ini_set('session.cookie_path', $this->path);
			}
		}
		session_set_save_handler(
			array('CakeSession','__open'),
			array('CakeSession', '__close'),
			array('Cache', 'read'),
			array('Cache', 'write'),
			array('Cache', 'delete'),
			array('Cache', 'gc')
		);
	break;
	default:
		$config = CONFIGS . Configure::read('Session.save') . '.php';

		if (is_file($config)) {
			require($config);
		}
	break;
}

//iMODE session settings.
//
if($ktai->is_imode()){
	
	ini_set('session.use_only_cookies', 0);
	$this->_userAgent = '';
	if(Configure::read('Security.level') == 'high'){
		Configure::write('Security.level', 'medium');
	}
	
	ini_set('url_rewriter.tags', 'a=href,area=href,frame=src,input=src,form=fakeentry,fieldset=');
	session_use_trans_sid(1);
}
