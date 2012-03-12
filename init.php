<?php
if(php_sapi_name() !== 'cli') {
	define('BASEPATH', str_replace("\\", "/", str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath('./'))));
} else {
	define('BASEPATH', realpath(dirname(__FILE__).'/../'));
}

// {{{ AUTOLOADER
function autoload($classname) {
	if(!class_exists($classname, false)) {
		$classPath = str_replace('_', '/', $classname).'.php';
		if(file_exists(dirname(__FILE__).'/../site/'.$classPath))
			@include(dirname(__FILE__).'/../site/'.$classPath);
		elseif(file_exists(dirname(__FILE__).'/'.$classPath))
			@include(dirname(__FILE__).'/'.$classPath);
	}
}
spl_autoload_register('autoload');
// }}}

// {{{ DEFAULT ERROR REPORTING
if(Config::siteInfo()->versionExtension !== '') {
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
} else {
	error_reporting(0);
	ini_set('display_errors', 'Off');
}
// }}}

if(php_sapi_name() !== 'cli') {
	// {{{ SESSION
	Session::sess(Config::siteInfo()->site.' '.Config::siteInfo()->version.Config::siteInfo()->versionExtension);
	// }}}

	// {{{ DETECT AJAX
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
	) {
		define('AJAX', true);
	} else {
		define('AJAX', false);
	}
	// }}}
} else {
	define('AJAX', false);
}
