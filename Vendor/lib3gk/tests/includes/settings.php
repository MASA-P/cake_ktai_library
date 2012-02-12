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
 
/* 
 * テスト用ユーザーエージェント
 */
define('LIB3GK_TEST_USER_AGENT_PC', 'Mozilla/5.0 (Windows NT 5.1; rv:2.0) Gecko/20100101 Firefox/4.0');
define('LIB3GK_TEST_USER_AGENT_DOCOMO', 'DoCoMo/2.0 P906i(c100;TB;W24H15)');
define('LIB3GK_TEST_USER_AGENT_SOFTBANK', 'SoftBank/1.0/840SH/SHJ001/0123456789 Browser/NetFront/3.5 Profile/MIDP-2.0 Configuration/CLDC-1.1');
define('LIB3GK_TEST_USER_AGENT_KDDI', 'HTTP_USER_AGENT=KDDI-SA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0');
define('LIB3GK_TEST_USER_AGENT_EMOBILE', 'emobile/1.0.0 (H11T; like Gecko; Wireless) NetFront/3.4');
define('LIB3GK_TEST_USER_AGENT_IPHONE', 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_0 like Mac OS X; ja-jp) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5A345 Safari/525.20');
define('LIB3GK_TEST_USER_AGENT_PHS', 'Mozilla/3.0(WILLCOM;JRC/WX310J/2;1/1/C128) NetFront/3.3');
define('LIB3GK_TEST_USER_AGENT_ANDROID', 'Mozilla/5.0 (Linux; U; Android 1.6; ja-jp; SonyEricssonSO-01B Build/R1EA018) AppleWebKit/528.5+ (KHTML, like Gecko) Version/3.1.2 Mobile Safari/525.20.1');

/* 
 * WEBサーバで入手できるサーバ情報の補完
 */
$_SERVER['REMOTE_ADDR'] = '192.168.1.1';
$_SERVER['HTTP_USER_AGENT'] = LIB3GK_TEST_USER_AGENT_PC;
