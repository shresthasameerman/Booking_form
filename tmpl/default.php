<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');

$moduleId = $module->id;
?>

<div class="whiteleaf-booking-module">
    <h3>Book Your Stay at White Leaf Resort</h3>
    <form action="<?php echo Uri::current(); ?>" method="post" id="bookingForm<?php echo $moduleId; ?>" class="d-flex flex-wrap align-items-center gap-2">
        <div class="form-group">
            <label for="check_in_<?php echo $moduleId; ?>" class="form-label">Check-in</label>
            <input type="date" id="check_in_<?php echo $moduleId; ?>" name="check_in" class="form-control form-control-sm" required>
        </div>
        
        <div class="form-group">
            <label for="check_out_<?php echo $moduleId; ?>" class="form-label">Check-out</label>
            <input type="date" id="check_out_<?php echo $moduleId; ?>" name="check_out" class="form-control form-control-sm" required>
        </div>
        
        <div class="form-group">
            <label for="room_type_<?php echo $moduleId; ?>" class="form-label">Room Type</label>
            <select id="room_type_<?php echo $moduleId; ?>" name="room_type" class="form-control form-control-sm" required>
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
            <label for="guests_<?php echo $moduleId; ?>" class="form-label">Guests</label>
            <select id="guests_<?php echo $moduleId; ?>" name="guests" class="form-control form-control-sm" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="guest_name_<?php echo $moduleId; ?>" class="form-label">Name</label>
            <input type="text" id="guest_name_<?php echo $moduleId; ?>" name="guest_name" class="form-control form-control-sm" required>
        </div>
        
        <div class="form-group">
            <label for="guest_email_<?php echo $moduleId; ?>" class="form-label">Email</label>
            <input type="email" id="guest_email_<?php echo $moduleId; ?>" name="guest_email" class="form-control form-control-sm" required>
        </div>
        
        <div class="form-group d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-sm">Book Now</button>
        </div>
        
        <input type="hidden" name="task" value="submitBooking">
        <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>
