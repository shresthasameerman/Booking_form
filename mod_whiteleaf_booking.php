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

// Debug current request
error_log('WhiteLeaf Booking Module - Task: ' . $task);

if ($task === 'submitBooking' && Session::checkToken()) {
    $bookingData = $input->getArray([
        'check_in' => 'string',
        'check_out' => 'string',
        'guests' => 'int',
        'num_children' => 'int',
        'children_ages' => 'array'
    ]);
    error_log('Submit Booking Data: ' . print_r($bookingData, true));
    require ModuleHelper::getLayoutPath('mod_whiteleaf_booking', 'room_details');
    return;
    
} elseif ($task === 'specialRequest' && Session::checkToken()) {
    $bookingData = $input->getArray([
        'check_in' => 'string',
        'check_out' => 'string',
        'room_quantity' => 'array',
        'guests' => 'int',
        'num_children' => 'int',
        'children_ages' => 'array'
    ]);
    error_log('Special Request Data: ' . print_r($bookingData, true));
    require ModuleHelper::getLayoutPath('mod_whiteleaf_booking', 'special_request');
    return;
    
} elseif ($task === 'confirmBooking' && Session::checkToken()) {
    error_log('Confirm Booking - Processing request');
    $result = $booking->processBooking($input);
    if ($result['success']) {
        $bookingData = $result['data'];
        error_log('Booking Successful: ' . print_r($bookingData, true));
        require ModuleHelper::getLayoutPath('mod_whiteleaf_booking', 'confirmation');
        return;
    } else {
        error_log('Booking Failed');
        // Fall through to default view
    }
}

try {
    // Get available rooms
    $rooms = $booking->getRooms();
    if (empty($rooms)) {
        error_log('No rooms found in the database');
    } else {
        error_log('Found ' . count($rooms) . ' rooms in the database');
    }
} catch (Exception $e) {
    error_log('Error loading rooms data: ' . $e->getMessage());
    Factory::getApplication()->enqueueMessage('Error loading rooms data', 'error');
    $rooms = [];
}

require ModuleHelper::getLayoutPath('mod_whiteleaf_booking');