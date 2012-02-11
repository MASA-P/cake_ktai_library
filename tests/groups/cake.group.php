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

class CakeGroupTest extends TestSuite {
	
	var $label = 'Ktai Library codes for CakePHP';
	
	function CakeGroupTest() {
		TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES.DS.'controllers');
		TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES.DS.'components');
		TestManager::addTestCasesFromDirectory($this, APP_TEST_CASES.DS.'helpers');
	}
}
