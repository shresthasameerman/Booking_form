document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    
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
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // Directly redirect to confirmation page without checking availability
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        window.location.href = 'index.php?option=com_whiteleafbooking&view=confirmation&' + params;
    });
});