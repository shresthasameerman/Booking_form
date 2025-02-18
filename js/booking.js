document.addEventListener('DOMContentLoaded', function() {
    const moduleIds = document.querySelectorAll('input[name="module_id"]');
    
    moduleIds.forEach(function(moduleIdInput) {
        const moduleId = moduleIdInput.value;
        const form = document.getElementById('bookingForm' + moduleId);
        const checkIn = document.getElementById('check_in_' + moduleId);
        const checkOut = document.getElementById('check_out_' + moduleId);
        
        // Set minimum date as today
        const today = new Date().toISOString().split('T')[0];
        checkIn.min = today;
        
        // Update checkout minimum date when checkin changes
        checkIn.addEventListener('change', function() {
            checkOut.min = checkIn.value;
            if (checkOut.value && checkOut.value < checkIn.value) {
                checkOut.value = checkIn.value;
            }
        });
    });
});