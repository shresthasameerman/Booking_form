<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;

// Load CSS and JavaScript
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_whiteleaf_booking', 'modules/mod_whiteleaf_booking/css/styles.css');
$wa->registerAndUseScript('mod_whiteleaf_booking', 'modules/mod_whiteleaf_booking/js/booking.js');

// Get module helper
require_once __DIR__ . '/helper.php';
$booking = new ModWhiteleafBookingHelper();

// Handle form submission
$input = Factory::getApplication()->input;
$task = $input->getString('task', '');
$bookingData = null;

if ($task === 'submitBooking' && Session::checkToken()) {
    $bookingData = $input->getArray([
        'check_in' => 'string',
        'check_out' => 'string',
        'room_type' => 'int',
        'guests' => 'int',
        'guest_name' => 'string',
        'guest_email' => 'string',
        'guest_phone' => 'string'
    ]);
    require ModuleHelper::getLayoutPath('mod_whiteleaf_booking', 'special_request');
    return;
} elseif ($task === 'confirmBooking' && Session::checkToken()) {
    $result = $booking->processBooking($input);
    if ($result['success']) {
        $bookingData = $result['data'];
        require ModuleHelper::getLayoutPath('mod_whiteleaf_booking', 'confirmation');
        return;
    }
}

try {
    // Get available rooms
    $rooms = $booking->getRooms();
} catch (Exception $e) {
    Factory::getApplication()->enqueueMessage('Error loading rooms data', 'error');
    $rooms = [];
}

require ModuleHelper::getLayoutPath('mod_whiteleaf_booking');