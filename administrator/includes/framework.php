<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Application
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/*
 * Joomla! system checks.
 */

@ini_set('magic_quotes_runtime', 0);
@ini_set('zend.ze1_compatibility_mode', '0');

/*
 * Installation check, and check on removal of the install directory.
 */
if (!file_exists(JPATH_CONFIGURATION.'/configuration.php') || (filesize(JPATH_CONFIGURATION.'/configuration.php') < 10) /*|| file_exists(JPATH_INSTALLATION.'/index.php')*/) {
	header('Location: ../installation/index.php');
	exit();
}

//
// Joomla system startup.
//

// Import the cms version library if necessary.
if (!class_exists('JVersion')) {
    require JPATH_ROOT.'/includes/version.php';
}

// System includes.
require_once JPATH_LIBRARIES.'/import.php';

// Pre-Load configuration.
ob_start();
require_once JPATH_CONFIGURATION.'/configuration.php';
ob_end_clean();

// System configuration.
$config = new JConfig();

// Set the error_reporting
switch ($config->error_reporting)
{
	case 'default':
	case '-1':
		break;

	case 'none':
	case '0':
		error_reporting(0);
		break;

	case 'simple':
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		ini_set('display_errors', 1);
		break;

	case 'maximum':
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		break;

	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
		break;

	default:
		error_reporting($config->error_reporting);
		ini_set('display_errors', 1);
		break;
}

define('JDEBUG', $config->debug);

unset($config);

/*
 * Joomla! framework loading.
 */

// System profiler.
if (JDEBUG) {
	jimport('joomla.error.profiler');
	$_PROFILER = JProfiler::getInstance('Application');
}

// Joomla! library imports.
jimport('joomla.application.menu');
jimport('joomla.user.user');
jimport('joomla.environment.uri');
jimport('joomla.filter.filterinput');
jimport('joomla.filter.filteroutput');
jimport('joomla.html.html');
jimport('joomla.html.parameter');
jimport('joomla.utilities.utility');
jimport('joomla.event.event');
jimport('joomla.event.dispatcher');
jimport('joomla.language.language');
jimport('joomla.utilities.string');
jimport('joomla.utilities.arrayhelper');
