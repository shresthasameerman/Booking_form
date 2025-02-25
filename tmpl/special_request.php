<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('behavior.keepalive'); // Prevents session timeout

$moduleId = $module->id;
$maxLength = 500; // Define maximum character length for special requests
?>

<div class="whiteleaf-booking-module">
    <h3><?php echo Text::_('SPECIAL REQUESTS'); ?></h3>
    
    <form action="<?php echo Uri::current(); ?>" method="post" 
          id="specialRequestForm<?php echo $moduleId; ?>" 
          class="needs-validation" novalidate>
        
        <div class="form-group mb-3">
            <label for="guest_name_<?php echo $moduleId; ?>" class="form-label"><i class="fas fa-user-circle"></i> Name</label>
            <input type="text" id="guest_name_<?php echo $moduleId; ?>" name="guest_name" class="form-control form-control-sm" required>
            <div class="invalid-feedback">Please provide your name.</div>
        </div>
        
        <div class="form-group mb-3">
            <label for="guest_email_<?php echo $moduleId; ?>" class="form-label"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="guest_email_<?php echo $moduleId; ?>" name="guest_email" class="form-control form-control-sm" required>
            <div class="invalid-feedback">Please provide a valid email address.</div>
        </div>
        
        <div class="form-group mb-3">
            <label for="guest_phone_<?php echo $moduleId; ?>" class="form-label"><i class="fas fa-phone"></i> Phone</label>
            <input type="tel" id="guest_phone_<?php echo $moduleId; ?>" name="guest_phone" class="form-control form-control-sm" placeholder="+1 123 456 7890" required>
            <div class="invalid-feedback">Please provide a phone number.</div>
        </div>
        
        <div class="form-group mb-3">
            <label for="special_requests_<?php echo $moduleId; ?>" class="form-label">
                <?php echo Text::_('MOD_WHITELEAF_BOOKING_SPECIAL_REQUESTS_LABEL'); ?>
                <small class="text-muted">(<?php echo Text::_('OPTIONAL'); ?>)</small>
            </label>
            <textarea id="special_requests_<?php echo $moduleId; ?>" 
                      name="special_requests" 
                      class="form-control"
                      rows="3"
                      maxlength="<?php echo $maxLength; ?>"
                      placeholder="<?php echo Text::_('If you have any special request for us to fulfill then please let us know'); ?>"
            ></textarea>
            <div class="char-count mt-1 small text-muted">
                <span id="charCount<?php echo $moduleId; ?>">0</span>/<?php echo $maxLength; ?>
            </div>
        </div>

        <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary">
                <?php echo Text::_('Confirm Booking'); ?>
            </button>
        </div>

        <?php 
        // Preserve all room quantity data
        if (isset($bookingData['room_quantity']) && is_array($bookingData['room_quantity'])) {
            foreach ($bookingData['room_quantity'] as $roomTitle => $quantity) {
                if ((int)$quantity > 0) {
                    echo '<input type="hidden" name="room_quantity[' . htmlspecialchars($roomTitle, ENT_QUOTES, 'UTF-8') . ']" value="' . htmlspecialchars($quantity, ENT_QUOTES, 'UTF-8') . '">';
                }
            }
        }
        
        // Preserve other booking data
        $preserveFields = ['check_in', 'check_out', 'guests', 'num_children']; 
        foreach ($preserveFields as $field) {
            if (isset($bookingData[$field])) {
                echo '<input type="hidden" name="' . $field . '" value="' . htmlspecialchars($bookingData[$field], ENT_QUOTES, 'UTF-8') . '">';
            }
        }
        
        // Handle children_ages array
        if (isset($bookingData['children_ages']) && is_array($bookingData['children_ages'])) {
            foreach ($bookingData['children_ages'] as $index => $age) {
                echo '<input type="hidden" name="children_ages[]" value="' . htmlspecialchars($age, ENT_QUOTES, 'UTF-8') . '">';
            }
        }
        ?>
        
        <input type="hidden" name="task" value="confirmBooking">
        <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('special_requests_<?php echo $moduleId; ?>');
    const charCount = document.getElementById('charCount<?php echo $moduleId; ?>');
    const maxLength = <?php echo $maxLength; ?>;

    textarea.addEventListener('input', function() {
        const remaining = this.value.length;
        charCount.textContent = remaining;
        
        if (remaining >= maxLength) {
            charCount.classList.add('text-danger');
        } else {
            charCount.classList.remove('text-danger');
        }
    });

    // Form validation
    const form = document.getElementById('specialRequestForm<?php echo $moduleId; ?>');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>