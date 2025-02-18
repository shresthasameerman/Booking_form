<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

// Load jQuery and Bootstrap if needed
HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');

$app = Factory::getApplication();
$input = $app->input;
$checkIn = $input->get('check_in', '', 'string');
$checkOut = $input->get('check_out', '', 'string');
$roomType = $input->get('room_type', '', 'int');
$guests = $input->get('guests', '', 'int');
$guestName = $input->get('guest_name', '', 'string');
$guestEmail = $input->get('guest_email', '', 'string');
?>

<div class="whiteleaf-booking-module">
    <div class="confirmation-message">
        <h3>Booking Confirmation</h3>
        <p>Thank you, <?php echo htmlspecialchars($guestName, ENT_QUOTES, 'UTF-8'); ?>!</p>
        <p>Your booking for a room from <?php echo htmlspecialchars($checkIn, ENT_QUOTES, 'UTF-8'); ?> to <?php echo htmlspecialchars($checkOut, ENT_QUOTES, 'UTF-8'); ?> has been confirmed.</p>
        <p>We have sent a confirmation email to <?php echo htmlspecialchars($guestEmail, ENT_QUOTES, 'UTF-8'); ?>.</p>
    </div>
</div>