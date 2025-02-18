<?php
// Ensure this file is being called within the Joomla framework
defined('_JEXEC') or die;

// Import required Joomla classes for MVC pattern and application functionality
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

/**
 * Main controller class for the Whiteleaf Booking component
 * Extends Joomla's BaseController to inherit core controller functionality
 */
class WhiteleafBookingController extends BaseController
{
    /**
     * Method to handle room availability checking and booking process
     * This method processes form submissions for room bookings
     * 
     * @return boolean True on success, False on validation failure
     */
    public function checkAvailability()
    {
        // Get the application input object to retrieve form data
        $input = Factory::getApplication()->input;
        
        // Define and retrieve an array of expected form fields with their data types
        // This provides input filtering for security
        $data = $input->getArray(array(
            'check_in' => 'string',     // Check-in date
            'check_out' => 'string',    // Check-out date
            'room_type' => 'int',       // Room type ID
            'guests' => 'int',          // Number of guests
            'guest_name' => 'string',   // Guest's name
            'guest_email' => 'string',  // Guest's email
            'option' => 'string',       // Component option (required for Joomla routing)
            'task' => 'string'          // Task name (required for Joomla routing)
        ));

        // Validate that all required fields are filled out
        // If any required field is empty, show error message and redirect back
        if (empty($data['check_in']) || empty($data['check_out']) || 
            empty($data['room_type']) || empty($data['guests']) || 
            empty($data['guest_name']) || empty($data['guest_email'])) {
            
            // Add error message to the application queue
            Factory::getApplication()->enqueueMessage('All fields are required', 'error');
            
            // Redirect back to the main booking form
            $this->setRedirect(Route::_('index.php?option=com_whiteleafbooking', false));
            return false;
        }

        // Currently skips actual availability check (TODO: Implement real availability checking)
        // Redirects to confirmation page with all booking details in URL parameters
        // The false parameter in Route::_ prevents URL encoding of the parameters
        $this->setRedirect(Route::_('index.php?option=com_whiteleafbooking&view=confirmation&check_in=' . 
            $data['check_in'] . '&check_out=' . $data['check_out'] . 
            '&room_type=' . $data['room_type'] . '&guests=' . $data['guests'] . 
            '&guest_name=' . $data['guest_name'] . '&guest_email=' . $data['guest_email'], false));
        return true;
    }
}