<?php
date_default_timezone_set("EST");

define('APP_FORK',  empty($argv[1]) || 'cli' != $argv[1]);

//App Directory (Absolute Location!)
if (!defined('APP_NAME')) define('APP_NAME', '7DaysManager');
if (!defined('APP_ROOT')) define('APP_ROOT', '/usr/local/etc/7daysManager/');
if (!defined('APP_UID')) define('APP_UID', 1500);
if (!defined('APP_GID')) define('APP_GID', 1500);
if (!defined('APP_PID')) define('APP_PID', '' . APP_ROOT . '' . APP_NAME . '.lock');

//Log Directory (Absolute Location!)
if (!defined('APP_LOG')) define('APP_LOG', '' . APP_ROOT . 'log/7dm_' . date('Ymd') . '.log');
if (!defined('DEBUG_LOGGING')) define('DEBUG_LOGGING', '1');

//DB Config
if (!defined('DB_HOST')) define('DB_HOST', '**HOST**');
if (!defined('DB_USER')) define('DB_USER', '**USER**');
if (!defined('DB_NAME')) define('DB_NAME', '**DB**');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', '**PASS**');
//////////////////////////////////////////////////////////
//MySQL Connection String
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$link) {
	die('Could not connect: ' .mysql_error());
}
$db_selected = mysql_select_db(DB_NAME, $link);
if (!$db_selected) {
	die('Can\'t use ' . DB_NAME . ': ' . mysql_error());
}
//////////////////////////////////////////////////////////

//Server API Information
if (!defined('API_HOST')) define('API_HOST', '**HOST');
if (!defined('API_PORT')) define('API_PORT', '**PORT**');
if (!defined('API_USER')) define('API_USER', '**USER**');
if (!defined('APT_PASS')) define('APT_PASS', '**PASS');

//Server Telnet Information
if (!defined('TELNET_HOST')) define('TELNET_HOST', API_HOST);
if (!defined('TELNET_PORT')) define('TELNET_PORT', '**PORT**');
if (!defined('TELNET_PASS')) define('TELNET_PASS', '**PASS**');

//Command Schedule (Seconds)
//Only modify this if you want to adjust when commands execute
if (!defined('syncGameTime')) define('syncGameTime', '5');
if (!defined('syncOnlinePlayers')) define('syncOnlinePlayers', '10');
if (!defined('MAX_RESULT')) define('MAX_RESULT', 100);
if (!defined('MIN_SLEEP')) define('MIN_SLEEP', 0);
if (!defined('MAX_SLEEP')) define('MAX_SLEEP', 4);




//************************
//** NOT FUNCTIONAL YET **
//************************
//Maximum Login Attempts
//$max_attempts = 5;

//Timeout (in seconds) after max attempts are reached
//$login_timeout = 300;

//ONLY set this if you want a moderator to verify users and not the users themselves, otherwise leave blank or comment out
//$admin_email = '';

//EMAIL SETTINGS
//SEND TEST EMAILS THROUGH FORM TO https://www.mail-tester.com GENERATED ADDRESS FOR SPAM SCORE
//$from_email = 'youremail@domain.com'; //Webmaster email
//$from_name = 'Test Email'; //"From name" displayed on email

//Find specific server settings at https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html
//$mailServerType = 'smtp';
//IF $mailServerType = 'smtp'
//$smtp_server = 'smtp.mail.domain.com';
//$smtp_user = 'youremail@domain.com';
//$smtp_pw = 'yourEmailPassword';
//$smtp_port = 465; //465 for ssl, 587 for tls, 25 for other
//$smtp_security = 'ssl';//ssl, tls or ''

//HTML Messages shown before URL in emails (the more
//$verifymsg = 'Click this link to verify your new account!'; //Verify email message
//$active_email = 'Your new account is now active! Click this link to log in!';//Active email message

//LOGIN FORM RESPONSE MESSAGES/ERRORS
//$signupthanks = 'Thank you for signing up! You will receive an email shortly confirming the verification of your account.';
//$activemsg = 'Your account has been verified! You may now login at <br><a href="'.$signin_url.'">'.$signin_url.'</a>';

//DO NOT TOUCH BELOW THIS LINE
//Unsets $admin_email based on various conditions (left blank, not valid email, etc)
//if (trim($admin_email, ' ') == '') {
//    unset($admin_email);
//} elseif (!filter_var($admin_email, FILTER_VALIDATE_EMAIL) == true) {
//    unset($admin_email);
//    echo $invalid_mod;
//};
//$invalid_mod = '$adminemail is not a valid email address';

//Makes readable version of timeout (in minutes). Do not change.
//$timeout_minutes = round(($login_timeout / 60), 1);
?>
