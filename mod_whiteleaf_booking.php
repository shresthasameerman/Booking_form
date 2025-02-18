<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;

// Load CSS and JavaScript
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_whiteleaf_booking', 'modules/mod_whiteleaf_booking/css/styles.css');
$wa->registerAndUseScript('mod_whiteleaf_booking', 'modules/mod_whiteleaf_booking/js/booking.js');

// Get module helper
require_once __DIR__ . '/helper.php';
$booking = new ModWhiteleafBookingHelper();

try {
    // Get available rooms
    $rooms = $booking->getRooms();
} catch (Exception $e) {
    // Log error but don't display to users
    Factory::getApplication()->enqueueMessage('Error loading rooms data', 'error');
    $rooms = [];
}

require ModuleHelper::getLayoutPath('mod_whiteleaf_booking');