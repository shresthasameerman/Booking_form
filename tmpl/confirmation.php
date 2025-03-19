<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');

$bookingNumber = $bookingData['booking_number'];
$totalPrice = $bookingData['total_price'];
$rooms = $bookingData['rooms'];
?>

<div class="whiteleaf-booking-module">
    <div class="confirmation-message">
        <h3>Booking Confirmation</h3>
        <div class="alert alert-success">
            <p>Thank you for your booking!</p>
            <p>Your booking has been confirmed with booking number: <strong><?php echo htmlspecialchars($bookingNumber, ENT_QUOTES, 'UTF-8'); ?></strong></p>
        </div>
        
        <div class="booking-details mt-4">
            <h4>Booking Details</h4>
            <table class="table">
                <tr>
                    <th>Booking Number:</th>
                    <td><?php echo htmlspecialchars($bookingNumber, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                    <th>Total Price:</th>
                    <td>$<?php echo number_format($totalPrice, 2); ?></td>
                </tr>
                <tr>
                    <th>Rooms:</th>
                    <td>
                        <?php foreach ($rooms as $roomTitle => $quantity): ?>
                            <p><?php echo htmlspecialchars($roomTitle, ENT_QUOTES, 'UTF-8'); ?> - Quantity: <?php echo htmlspecialchars($quantity, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endforeach; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="booking-notes mt-4">
            <p>A confirmation email will be sent to you shortly.</p>
            <p>Please keep your booking number for future reference.</p>
            <p><strong>We will reach out to you within 24 hours of booking.</strong></p>
        </div>
        
        <div class="return-home mt-4">
            <a href="<?php echo JUri::base(); ?>" class="btn btn-custom">Return to Home Page</a>
        </div>
    </div>
</div>