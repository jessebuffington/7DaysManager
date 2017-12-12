#!/usr/bin/php -c/etc/phpd.ini
<?php
require 'includes/appConfig.php';
require 'includes/functions.php';
chdir(APP_ROOT);
if (PHP_SAPI !== 'cli') {
	exit;
}
if (APP_FORK && 0 !== pcntl_fork()) {
	exit;
}
Main::log(E_NOTICE, APP_NAME . ' started at ' . date(DATE_RFC822));
// Used for signals
declare(ticks = 1);
Main::registerSignal();
set_error_handler("Main::handleError");
if (APP_FORK) {
	Main::registerConsole();
	Main::registerEnv();
	/* Reduce verbosity, we don't have STDOUT/STDERR open anymore*/
	ini_set("display_errors", 0);
}
Main::loop();
/* Delete PID file after shutdown */
unlink(PID_FILE);
Main::log(E_NOTICE, APP_NAME . ' shut down normally at ' . date(DATE_RFC822));
