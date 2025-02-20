<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');

$booking = $bookingData['booking'];
$room = $bookingData['room'];
$nights = $bookingData['nights'];
?>

<div class="whiteleaf-booking-module">
    <div class="confirmation-message">
        <h3>Booking Confirmation</h3>
        <div class="alert alert-success">
            <p>Thank you, <?php echo htmlspecialchars($booking->guest_name, ENT_QUOTES, 'UTF-8'); ?>!</p>
            <p>Your booking has been confirmed with booking number: <strong><?php echo htmlspecialchars($booking->booking_number, ENT_QUOTES, 'UTF-8'); ?></strong></p>
        </div>
        
        <div class="booking-details mt-4">
            <h4>Booking Details</h4>
            <table class="table">
                <tr>
                    <th>Room Type:</th>
                    <td><?php echo htmlspecialchars($room->title, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th>Check-in:</th>
                    <td><?php echo HTMLHelper::_('date', $booking->check_in, 'l, F j, Y'); ?></td>
                </tr>
                <tr>
                    <th>Check-out:</th>
                    <td><?php echo HTMLHelper::_('date', $booking->check_out, 'l, F j, Y'); ?></td>
                </tr>
                <tr>
                    <th>Number of Nights:</th>
                    <td><?php echo $nights; ?></td>
                </tr>
                <tr>
                    <th>Number of Guests:</th>
                    <td><?php echo $booking->num_adults; ?></td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td><?php echo htmlspecialchars($booking->guest_phone, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th>Total Price:</th>
                    <td>$<?php echo number_format($booking->total_price, 2); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="booking-notes mt-4">
            <p>A confirmation email has been sent to <?php echo htmlspecialchars($booking->guest_email, ENT_QUOTES, 'UTF-8'); ?>.</p>
            <p>Please keep your booking number for future reference.</p>
        </div>
    </div>
</div>