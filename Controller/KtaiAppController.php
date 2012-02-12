<?php
/*
 * CakeKtaiLibrary, supports Japanese mobile phone sites coding.
 * This provides many functions such as a carrier check to use Referer or E-mail, 
 * conversion of an Emoji, and more.
 *
 * PHP versions 5
 *
 * CakeKtaiLibrary for CakePHP2.x
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

//******************************
//  NOTICE
//******************************
//If you use session in your mobile phone site, copy this file 
//to app/controllers directory or paste nessesary parts in this file to your 
//AppController class.

class KtaiAppController extends AppController {
	
	var $components = array('CakeKtaiLibrary.Ktai');
	
	//----------------------------------------------------------
	//Redirect override.
	//If iMODE access or use_redirect_session_id is true, 
	// adding session id to url param.
	//----------------------------------------------------------
	function __redirect_url($url){
		
		if(isset($this->Ktai)){
			if($this->Ktai->_options['enable_ktai_session'] && 
				($this->Ktai->_options['use_redirect_session_id'] || $this->Ktai->is_imode())){
				if(!is_array($url)){
					if(preg_match('|^http[s]?://|', $url)){
						return $url;
					}
					$url = Router::parse($url);
				}
				if(!isset($url['?'])){
					$url['?'] = array();
				}
				$url['?'][session_name()] = session_id();
			}
		}
		return $url;
	}
	function redirect($url, $status = null, $exit = true){
		return parent::redirect($this->__redirect_url($url), $status, $exit);
	}
}
