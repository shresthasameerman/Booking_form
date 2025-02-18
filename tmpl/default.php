<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

// Load jQuery and Bootstrap if needed
HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');
?>

<div class="whiteleaf-booking-module">
    <h3>Book Your Stay at White Leaf Resort</h3>
    <form action="index.php" method="post" id="bookingForm" class="d-flex flex-wrap align-items-center gap-2">
        <div class="form-group">
            <label for="check_in" class="form-label">Check-in</label>
            <input type="date" id="check_in" name="check_in" class="form-control form-control-sm" required>
        </div>
        
        <div class="form-group">
            <label for="check_out" class="form-label">Check-out</label>
            <input type="date" id="check_out" name="check_out" class="form-control form-control-sm" required>
        </div>
        
        <div class="form-group">
            <label for="room_type" class="form-label">Room Type</label>
            <select id="room_type" name="room_type" class="form-control form-control-sm" required>
                <option value="">Select Room</option>
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo htmlspecialchars($room->id, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($room->title, ENT_QUOTES, 'UTF-8'); ?> - 
                            <?php echo number_format($room->price, 2); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No rooms available</option>
                <?php endif; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="guests" class="form-label">Guests</label>
            <select id="guests" name="guests" class="form-control form-control-sm" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="guest_name" class="form-label">Name</label>
            <input type="text" id="guest_name" name="guest_name" class="form-control form-control-sm" required>
        </div>
        
        <div class="form-group">
            <label for="guest_email" class="form-label">Email</label>
            <input type="email" id="guest_email" name="guest_email" class="form-control form-control-sm" required>
        </div>
        
        <div class="form-group d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-sm">Check Availability</button>
        </div>
        
        <input type="hidden" name="option" value="com_whiteleafbooking">
        <input type="hidden" name="task" value="booking.checkAvailability">
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>