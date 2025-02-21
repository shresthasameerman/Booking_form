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
            <label for="special_requests_<?php echo $moduleId; ?>" class="form-label">
                <?php echo Text::_('MOD_WHITELEAF_BOOKING_SPECIAL_REQUESTS_LABEL'); ?>
                <small class="text-muted">(<?php echo Text::_('OPTIONAL'); ?>)</small>
            </label>
            <textarea id="special_requests_<?php echo $moduleId; ?>" 
                      name="special_requests" 
                      class="form-control"
                      rows="3"
                      maxlength="<?php echo $maxLength; ?>"
                      placeholder="<?php echo Text::_('If you have any special request for us to full fill then please let us know'); ?>"
            ></textarea>
            <div class="char-count mt-1 small text-muted">
                <span id="charCount<?php echo $moduleId; ?>">0</span>/<?php echo $maxLength; ?>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <?php echo Text::_('Confirm Booking'); ?>
            </button>
        </div>

        <?php foreach ($bookingData as $key => $value): ?>
            <input type="hidden" 
                   name="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>" 
                   value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>">
        <?php endforeach; ?>
        
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