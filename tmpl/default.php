<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');

// Add Flatpickr resources
$doc = JFactory::getDocument();
$doc->addStyleSheet('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
$doc->addScript('https://cdn.jsdelivr.net/npm/flatpickr');

// After Flatpickr resources
$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');

$moduleId = $module->id;
?>

<div class="whiteleaf-booking-module">
    <h3>Book Now</h3>
    <form action="<?php echo Uri::current(); ?>" method="post" id="bookingForm<?php echo $moduleId; ?>" class="d-flex flex-wrap align-items-center gap-2">
        <div class="form-group">
            <label for="check_in_<?php echo $moduleId; ?>" class="form-label"><i class="fas fa-calendar-alt"></i> Check-in</label>
            <input type="text" id="check_in_<?php echo $moduleId; ?>" name="check_in" class="flatpickr-input form-control form-control-sm" readonly="readonly" required>
        </div>
        
        <div class="form-group">
            <label for="check_out_<?php echo $moduleId; ?>" class="form-label"><i class="fas fa-calendar-alt"></i> Check-out</label>
            <input type="text" id="check_out_<?php echo $moduleId; ?>" name="check_out" class="flatpickr-input form-control form-control-sm" readonly="readonly" required>
        </div>
        
        <div class="form-group">
            <label for="guests_<?php echo $moduleId; ?>" class="form-label"><i class="fas fa-user"></i> Guests</label>
            <select id="guests_<?php echo $moduleId; ?>" name="guests" class="form-control form-control-sm" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="num_children_<?php echo $moduleId; ?>" class="form-label"><i class="fas fa-child"></i> Children</label>
            <select id="num_children_<?php echo $moduleId; ?>" name="num_children" class="form-control form-control-sm" required>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>

        <div id="children_ages_container_<?php echo $moduleId; ?>" class="form-group" style="display: none;">
            <label class="form-label"><i class="fas fa-birthday-cake"></i> Children's Ages</label>
            <div id="children_ages_inputs_<?php echo $moduleId; ?>" class="d-flex flex-column gap-2">
            </div>
        </div>
        
        <div class="form-group d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Book Now</button>
        </div>
        
        <input type="hidden" name="task" value="submitBooking">
        <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>
    <style>
    .whiteleaf-booking-module .form-label i {
        margin-right: 5px;
        color: #28a745; /* Bootstrap's green color */
    }

    .whiteleaf-booking-module .btn i {
        margin-right: 5px;
    }

    .whiteleaf-booking-module .form-label {
        color: #28a745;
    }

    .whiteleaf-booking-module .form-control {
        border-color: #28a745;
    }

    .whiteleaf-booking-module .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .whiteleaf-booking-module .btn-primary {
        background-color: #28a745;
        border-color: #28a745;
    }

    .whiteleaf-booking-module .btn-primary:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .child-age-input {
        width: 100%;         /* Make inputs full width */
        max-width: 200px;    /* But limit their maximum width */
        margin-bottom: 0.5rem;
    }
    
    #children_ages_container_<?php echo $moduleId; ?> {
        margin-top: 0.5rem;
    }

    #children_ages_inputs_<?php echo $moduleId; ?> {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    /* Style for Flatpickr elements */
    .flatpickr-input {
        border-color: #28a745 !important;
    }

    .flatpickr-day.selected, 
    .flatpickr-day.startRange, 
    .flatpickr-day.endRange, 
    .flatpickr-day.selected.inRange, 
    .flatpickr-day.startRange.inRange, 
    .flatpickr-day.endRange.inRange, 
    .flatpickr-day.selected:focus, 
    .flatpickr-day.startRange:focus, 
    .flatpickr-day.endRange:focus {
        background: #28a745 !important;
        border-color: #28a745 !important;
    }
    </style>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Common configuration for both date pickers
    const commonConfig = {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today",
        disableMobile: "true"
    };

    // Initialize check-in date picker
    const checkInPicker = flatpickr("#check_in_<?php echo $moduleId; ?>", {
        ...commonConfig,
        onChange: function(selectedDates) {
            // Update check-out minimum date when check-in is selected
            checkOutPicker.set('minDate', selectedDates[0]);
        }
    });

    // Initialize check-out date picker
    const checkOutPicker = flatpickr("#check_out_<?php echo $moduleId; ?>", {
        ...commonConfig,
        minDate: "today"
    });

    // Add children ages handler
    const numChildrenSelect = document.getElementById('num_children_<?php echo $moduleId; ?>');
    const agesContainer = document.getElementById('children_ages_container_<?php echo $moduleId; ?>');
    const agesInputsContainer = document.getElementById('children_ages_inputs_<?php echo $moduleId; ?>');

    numChildrenSelect.addEventListener('change', function() {
        const numChildren = parseInt(this.value);
        agesContainer.style.display = numChildren > 0 ? 'block' : 'none';
        agesInputsContainer.innerHTML = '';

        for(let i = 0; i < numChildren; i++) {
            const childDiv = document.createElement('div');
            childDiv.className = 'child-age-input';
            childDiv.innerHTML = `
                <select name="children_ages[]" class="form-control form-control-sm" required>
                    <option value="">Child ${i + 1} Age</option>
                    ${Array.from({length: 18}, (_, j) => 
                        `<option value="${j}">${j} ${j === 1 ? 'year' : 'years'}</option>`
                    ).join('')}
                </select>
            `;
            agesInputsContainer.appendChild(childDiv);
        }
    });
});
</script>