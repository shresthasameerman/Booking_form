<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');

$rooms = $booking->getRooms();
$moduleId = $module->id;
?>

<div class="whiteleaf-booking-module">
    <h3>Available Rooms</h3>
    <form action="<?php echo Uri::current(); ?>" method="post" id="roomSelectionForm<?php echo $moduleId; ?>">
        <div class="room-list">
            <?php foreach ($rooms as $room): ?>
                <div class="room-item">
                    <h4><?php echo htmlspecialchars($room->title, ENT_QUOTES, 'UTF-8'); ?></h4>
                    <p><?php echo htmlspecialchars($room->description, ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>Price: $<?php echo number_format($room->price, 2); ?></p>
                    <p>Capacity: <?php echo htmlspecialchars($room->capacity, ENT_QUOTES, 'UTF-8'); ?> guests</p>
                    <div class="room-selection">
                        <label for="room_select_<?php echo $room->id; ?>" class="form-label">Select Room</label>
                        <input type="checkbox" id="room_select_<?php echo $room->id; ?>" class="room-select-checkbox" data-room-id="<?php echo $room->id; ?>" data-room-title="<?php echo htmlspecialchars($room->title, ENT_QUOTES, 'UTF-8'); ?>" value="1">
                    </div>
                    <div class="room-quantity" id="quantity_container_<?php echo $room->id; ?>" style="display: none;">
                        <label for="room_quantity_<?php echo $room->id; ?>" class="form-label">Number of Rooms</label>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="decreaseQuantity(<?php echo $room->id; ?>)">-</button>
                        <input type="number" id="room_quantity_<?php echo $room->id; ?>" name="room_quantity[<?php echo htmlspecialchars($room->title, ENT_QUOTES, 'UTF-8'); ?>]" value="0" min="0" readonly>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="increaseQuantity(<?php echo $room->id; ?>)">+</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Previous form data -->
        <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($input->getString('check_in'), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($input->getString('check_out'), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="guests" value="<?php echo htmlspecialchars($input->getInt('guests'), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="num_children" value="<?php echo htmlspecialchars($input->getInt('num_children'), ENT_QUOTES, 'UTF-8'); ?>">
        
        <?php 
        $childrenAges = $input->get('children_ages', [], 'array');
        if (!empty($childrenAges)) {
            echo '<input type="hidden" name="children_ages" value="' . htmlspecialchars(json_encode($childrenAges), ENT_QUOTES, 'UTF-8') . '">';
        }
        ?>
        
        <input type="hidden" name="task" value="specialRequest">
        <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
        <?php echo HTMLHelper::_('form.token'); ?>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary" id="nextButton" disabled>Next</button>
        </div>
    </form>
    
    <style>
        .whiteleaf-booking-module .room-list {
            display: flex;
            flex-direction: column;  /* Changed from row (default) to column */
            gap: 1rem;
            margin-bottom: 20px;
        }
        .whiteleaf-booking-module .room-item {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            width: 100%;         /* Changed from flex/max-width calculations */
            margin-bottom: 1rem; /* Added margin bottom for spacing */
            color: #333;
        }
        .whiteleaf-booking-module .room-item h4 {
            margin-top: 0;
        }
        .room-selection {
            margin-top: 1rem;
        }
        .room-quantity {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .room-quantity input {
            width: 50px;
            text-align: center;
        }
        /* Removed media query since we're using vertical layout by default */
    </style>
</div>

<script>
function increaseQuantity(roomId) {
    const input = document.getElementById('room_quantity_' + roomId);
    input.value = parseInt(input.value) + 1;
}

function decreaseQuantity(roomId) {
    const input = document.getElementById('room_quantity_' + roomId);
    if (input.value > 0) {
        input.value = parseInt(input.value) - 1;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const roomSelects = document.querySelectorAll('.room-select-checkbox');
    const nextButton = document.getElementById('nextButton');
    
    function checkRoomSelection() {
        let atLeastOneSelected = false;
        let totalRooms = 0;
        
        roomSelects.forEach(function(checkbox) {
            if (checkbox.checked) {
                const roomId = checkbox.getAttribute('data-room-id');
                const quantityInput = document.getElementById('room_quantity_' + roomId);
                const quantity = parseInt(quantityInput.value);
                
                if (quantity > 0) {
                    atLeastOneSelected = true;
                    totalRooms += quantity;
                }
            }
        });
        
        nextButton.disabled = totalRooms === 0;
    }
    
    roomSelects.forEach(function(roomSelect) {
        roomSelect.addEventListener('change', function() {
            const roomId = this.getAttribute('data-room-id');
            const quantityContainer = document.getElementById('quantity_container_' + roomId);
            const quantityInput = document.getElementById('room_quantity_' + roomId);
            
            if (this.checked) {
                quantityContainer.style.display = 'flex';
                quantityInput.disabled = false;
                // Set to 1 when checked
                quantityInput.value = 1;
            } else {
                quantityContainer.style.display = 'none';
                quantityInput.disabled = true;
                // Reset to 0 when unchecked
                quantityInput.value = 0;
            }
            
            checkRoomSelection();
        });
    });
    
    // Monitor for quantity changes
    document.querySelectorAll('[id^="room_quantity_"]').forEach(function(input) {
        input.addEventListener('change', checkRoomSelection);
    });
});
</script>