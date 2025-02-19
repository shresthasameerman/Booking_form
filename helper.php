<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date;

class ModWhiteleafBookingHelper
{
    public function getRooms()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__whiteleaf_rooms'))
            ->where($db->quoteName('published') . ' = 1');
        $db->setQuery($query);

        try {
            return $db->loadObjectList();
        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage('Error fetching rooms: ' . $e->getMessage(), 'error');
            return [];
        }
    }

    public function processBooking($input)
    {
        // Get form data
        $data = $input->getArray([
            'check_in' => 'string',
            'check_out' => 'string',
            'room_type' => 'int',
            'guests' => 'int',
            'guest_name' => 'string',
            'guest_email' => 'string',
            'guest_phone' => 'string'
        ]);

        // Validate data
        if (empty($data['check_in']) || empty($data['check_out']) || 
            empty($data['room_type']) || empty($data['guests']) || 
            empty($data['guest_name']) || empty($data['guest_email']) || 
            empty($data['guest_phone'])) {
            Factory::getApplication()->enqueueMessage('All fields are required', 'error');
            return ['success' => false];
        }

        try {
            $db = Factory::getDbo();
            
            // Generate booking number
            $bookingNumber = 'WL' . date('Ymd') . rand(1000, 9999);
            
            // Get room details
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->quoteName('#__whiteleaf_rooms'))
                ->where($db->quoteName('id') . ' = ' . $db->quote($data['room_type']));
            
            $db->setQuery($query);
            $room = $db->loadObject();
            
            if (!$room) {
                throw new Exception('Room not found');
            }

            // Calculate total price
            $checkIn = new Date($data['check_in']);
            $checkOut = new Date($data['check_out']);
            $nights = floor(($checkOut->toUnix() - $checkIn->toUnix()) / (60 * 60 * 24));
            $totalPrice = $room->price * $nights;

            // Insert booking
            $booking = (object)[
                'booking_number' => $bookingNumber,
                'room_id' => $data['room_type'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'guest_name' => $data['guest_name'],
                'guest_email' => $data['guest_email'],
                'guest_phone' => $data['guest_phone'],
                'num_adults' => $data['guests'],
                'total_price' => $totalPrice,
                'booking_status' => 'confirmed',
                'payment_status' => 'pending',
                'created' => (new Date())->toSql()
            ];

            $db->insertObject('#__whiteleaf_bookings', $booking);

            // Return success with booking data
            return [
                'success' => true,
                'data' => [
                    'booking' => $booking,
                    'room' => $room,
                    'nights' => $nights
                ]
            ];

        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage('Booking failed: ' . $e->getMessage(), 'error');
            return ['success' => false];
        }
    }
}