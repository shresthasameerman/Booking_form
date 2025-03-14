<?php
// Prevent direct access to this file
defined('_JEXEC') or die;

// Import required Joomla classes
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

// Load required frameworks
HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');

// Add required external resources
$doc = JFactory::getDocument();
// Add Flatpickr date picker resources
$doc->addStyleSheet('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
$doc->addScript('https://cdn.jsdelivr.net/npm/flatpickr');
// Add Font Awesome icons
$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
// Add Bootstrap tooltip
$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js');

// Get unique module ID for multiple instances
$moduleId = $module->id;
?>

<div class="whiteleaf-booking-module">
    <h3>Book Now</h3>
    <!-- Booking form with flex layout -->
    <form action="<?php echo Uri::current(); ?>" method="post" id="bookingForm<?php echo $moduleId; ?>" class="d-flex flex-wrap align-items-center gap-2">
        <!-- Check-in date input -->
        <div class="form-group">
            <label for="check_in_<?php echo $moduleId; ?>" class="form-label">
                <i class="fas fa-calendar-alt"></i> Check-in
            </label>
            <input type="text" id="check_in_<?php echo $moduleId; ?>" name="check_in" 
                   class="flatpickr-input form-control form-control-sm" readonly="readonly" required>
        </div>
        
        <!-- Check-out date input -->
        <div class="form-group">
            <label for="check_out_<?php echo $moduleId; ?>" class="form-label">
                <i class="fas fa-calendar-alt"></i> Check-out
            </label>
            <input type="text" id="check_out_<?php echo $moduleId; ?>" name="check_out" 
                   class="flatpickr-input form-control form-control-sm" readonly="readonly" required>
        </div>
        
        <!-- Rooms counter with +/- buttons -->
        <div class="form-group">
            <label for="rooms_<?php echo $moduleId; ?>" class="form-label">
                <i class="fas fa-bed"></i> Rooms
            </label>
            <div class="input-group">
                <button type="button" class="btn btn-outline-success btn-sm" onclick="decrementRooms(<?php echo $moduleId; ?>)">
                    <i class="fas fa-minus"></i>
                </button>
                <input type="number" id="rooms_<?php echo $moduleId; ?>" name="rooms" 
                       class="form-control form-control-sm text-center" value="1" min="1" max="5" readonly required>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="incrementRooms(<?php echo $moduleId; ?>)">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        
        <!-- Adults counter with +/- buttons and info icon -->
        <div class="form-group">
            <label for="guests_<?php echo $moduleId; ?>" class="form-label">
                <i class="fas fa-user"></i> Adults
                <i class="fas fa-info-circle text-primary info-icon" 
                   data-bs-toggle="tooltip" 
                   data-bs-placement="top" 
                   title="Maximum 4 guests per room"></i>
            </label>
            <div class="input-group">
                <button type="button" class="btn btn-outline-success btn-sm" onclick="decrementGuests(<?php echo $moduleId; ?>)">
                    <i class="fas fa-minus"></i>
                </button>
                <input type="number" id="guests_<?php echo $moduleId; ?>" name="guests" 
                       class="form-control form-control-sm text-center" value="1" min="1" readonly required>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="incrementGuests(<?php echo $moduleId; ?>)">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        
        <!-- Children counter with +/- buttons -->
        <div class="form-group">
            <label for="num_children_<?php echo $moduleId; ?>" class="form-label">
                <i class="fas fa-child"></i> Children
            </label>
            <div class="input-group">
                <button type="button" class="btn btn-outline-success btn-sm" onclick="decrementChildren(<?php echo $moduleId; ?>)">
                    <i class="fas fa-minus"></i>
                </button>
                <input type="number" id="num_children_<?php echo $moduleId; ?>" name="num_children" 
                       class="form-control form-control-sm text-center" value="0" min="0" max="3" readonly required>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="incrementChildren(<?php echo $moduleId; ?>)">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <!-- Children ages container (hidden by default) -->
        <div id="children_ages_container_<?php echo $moduleId; ?>" class="form-group" style="display: none;">
            <label class="form-label">
                <i class="fas fa-birthday-cake"></i> Children's Ages
            </label>
            <div id="children_ages_inputs_<?php echo $moduleId; ?>" class="d-flex flex-column gap-2">
            </div>
        </div>
        
        <!-- Submit button -->
        <div class="form-group d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-check"></i> Book Now
            </button>
        </div>
        
        <!-- Hidden fields -->
        <input type="hidden" name="task" value="submitBooking">
        <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
        <?php echo HTMLHelper::_('form.token'); ?>
    </form>

    <!-- Module styles -->
    <style>
        /* Icon styling */
        .whiteleaf-booking-module .form-label i {
            margin-right: 5px;
            color: #28a745;
        }

        .whiteleaf-booking-module .btn i {
            margin-right: 5px;
        }

        /* Info icon styling */
        .whiteleaf-booking-module .info-icon {
            color: #17a2b8 !important;
            font-size: 0.85em;
            cursor: pointer;
            margin-left: 5px;
        }

        /* Form element styling */
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

        /* Button styling */
        .whiteleaf-booking-module .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .whiteleaf-booking-module .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        /* Children age inputs styling */
        .child-age-input {
            width: 100%;
            max-width: 200px;
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

        /* Flatpickr customization */
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

        /* Input group styling */
        .input-group {
            width: auto;
        }

        .input-group input[type="number"] {
            width: 60px;
            text-align: center;
        }

        .input-group .btn {
            padding: 0.25rem 0.5rem;
        }

        .input-group .btn:hover {
            background-color: #28a745;
            color: white;
        }

        /* Add this CSS inside your existing <style> tag */
        .whiteleaf-booking-module input[type="number"]::-webkit-inner-spin-button,
        .whiteleaf-booking-module input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .whiteleaf-booking-module input[type="number"] {
            -moz-appearance: textfield;
        }

        /* Tooltip styling */
        .tooltip {
            font-size: 0.85em;
        }
        
        .tooltip-inner {
            background-color: #28a745;
            max-width: 200px;
        }
        
        .bs-tooltip-top .tooltip-arrow::before {
            border-top-color: #28a745;
        }
    </style>
</div>

<!-- JavaScript functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Flatpickr date picker configuration
    const commonConfig = {
        enableTime: false,        // Disable time selection
        dateFormat: "Y-m-d",     // Set date format
        minDate: "today",        // Prevent past date selection
        disableMobile: "true"    // Use custom picker on mobile
    };

    // Initialize check-in date picker
    const checkInPicker = flatpickr("#check_in_<?php echo $moduleId; ?>", {
        ...commonConfig,
        onChange: function(selectedDates) {
            // Update check-out minimum date
            checkOutPicker.set('minDate', selectedDates[0]);
            
            // If no check-out date is selected or if it's before the new check-in date
            const checkOutDate = checkOutPicker.selectedDates[0];
            if (!checkOutDate || checkOutDate <= selectedDates[0]) {
                // Open the check-out picker automatically
                setTimeout(() => {
                    checkOutPicker.open();
                }, 100);
            }
        }
    });

    // Initialize check-out date picker
    const checkOutPicker = flatpickr("#check_out_<?php echo $moduleId; ?>", {
        ...commonConfig,
        minDate: "today",
        onOpen: function() {
            // When opening check-out picker, ensure minimum date is set to check-in date if selected
            const checkInDate = checkInPicker.selectedDates[0];
            if (checkInDate) {
                this.set('minDate', checkInDate);
            }
        }
    });

    // Children ages functionality
    const numChildrenSelect = document.getElementById('num_children_<?php echo $moduleId; ?>');
    const agesContainer = document.getElementById('children_ages_container_<?php echo $moduleId; ?>');
    const agesInputsContainer = document.getElementById('children_ages_inputs_<?php echo $moduleId; ?>');

    // Handle children count changes
    numChildrenSelect.addEventListener('change', function() {
        const numChildren = parseInt(this.value);
        agesContainer.style.display = numChildren > 0 ? 'block' : 'none';
        agesInputsContainer.innerHTML = '';

        // Generate age inputs for each child
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
    
    // Adults counter functions
    const MAX_GUESTS_PER_ROOM = 4; // Adjust this value as needed

    // Updated Adults counter functions
    window.incrementGuests = function(moduleId) {
        const guestsInput = document.getElementById('guests_' + moduleId);
        const roomsInput = document.getElementById('rooms_' + moduleId);
        const currentGuests = parseInt(guestsInput.value);
        const newGuestCount = currentGuests + 1;
        
        // Calculate required rooms
        const requiredRooms = Math.ceil(newGuestCount / MAX_GUESTS_PER_ROOM);
        
        // Update rooms if necessary
        if (requiredRooms > parseInt(roomsInput.value)) {
            if (requiredRooms <= parseInt(roomsInput.max)) {
                roomsInput.value = requiredRooms;
            } else {
                // If max rooms reached, limit guests
                return;
            }
        }
        
        guestsInput.value = newGuestCount;
    }

    window.decrementGuests = function(moduleId) {
        const guestsInput = document.getElementById('guests_' + moduleId);
        const roomsInput = document.getElementById('rooms_' + moduleId);
        const currentGuests = parseInt(guestsInput.value);
        
        if (currentGuests > parseInt(guestsInput.min)) {
            const newGuestCount = currentGuests - 1;
            guestsInput.value = newGuestCount;
            
            // Calculate required rooms
            const requiredRooms = Math.ceil(newGuestCount / MAX_GUESTS_PER_ROOM);
            
            // Update rooms if possible
            if (requiredRooms < parseInt(roomsInput.value)) {
                roomsInput.value = Math.max(requiredRooms, parseInt(roomsInput.min));
            }
        }
    }

    // Rooms counter functions
    window.incrementRooms = function(moduleId) {
        const roomsInput = document.getElementById('rooms_' + moduleId);
        const guestsInput = document.getElementById('guests_' + moduleId);
        const currentValue = parseInt(roomsInput.value);
        
        if (currentValue < parseInt(roomsInput.max)) {
            roomsInput.value = currentValue + 1;
            // Calculate max possible guests with new room count
            const maxGuests = parseInt(roomsInput.value) * MAX_GUESTS_PER_ROOM;
            // Update max attribute on guests input
            guestsInput.setAttribute('max', maxGuests);
        }
    }

    window.decrementRooms = function(moduleId) {
        const roomsInput = document.getElementById('rooms_' + moduleId);
        const guestsInput = document.getElementById('guests_' + moduleId);
        const currentValue = parseInt(roomsInput.value);
        
        if (currentValue > parseInt(roomsInput.min)) {
            const newRoomCount = currentValue - 1;
            const maxGuests = newRoomCount * MAX_GUESTS_PER_ROOM;
            
            // Check if current guest count exceeds new max
            if (parseInt(guestsInput.value) > maxGuests) {
                guestsInput.value = maxGuests;
            }
            
            roomsInput.value = newRoomCount;
            // Update max attribute on guests input
            guestsInput.setAttribute('max', maxGuests);
        }
    }

    // Children counter functions
    window.incrementChildren = function(moduleId) {
        const childrenInput = document.getElementById('num_children_' + moduleId);
        const currentValue = parseInt(childrenInput.value);
        if (currentValue < parseInt(childrenInput.max)) {
            childrenInput.value = currentValue + 1;
            updateChildrenAges(moduleId);
        }
    }

    window.decrementChildren = function(moduleId) {
        const childrenInput = document.getElementById('num_children_' + moduleId);
        const currentValue = parseInt(childrenInput.value);
        if (currentValue > parseInt(childrenInput.min)) {
            childrenInput.value = currentValue - 1;
            updateChildrenAges(moduleId);
        }
    }

    // Update children ages inputs
    function updateChildrenAges(moduleId) {
        const numChildren = parseInt(document.getElementById('num_children_' + moduleId).value);
        const agesContainer = document.getElementById('children_ages_container_' + moduleId);
        const agesInputsContainer = document.getElementById('children_ages_inputs_' + moduleId);

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
    }

    // Update onclick handlers for existing buttons
    document.querySelectorAll('.btn[onclick^="increment"]').forEach(button => {
        const originalOnclick = button.getAttribute('onclick');
        button.setAttribute('onclick', `${originalOnclick}(${moduleId})`);
    });

    document.querySelectorAll('.btn[onclick^="decrement"]').forEach(button => {
        const originalOnclick = button.getAttribute('onclick');
        button.setAttribute('onclick', `${originalOnclick}(${moduleId})`);
    });
});
</script>