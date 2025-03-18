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
            'room_quantity' => 'array',
            'guests' => 'int',
            'guest_name' => 'string',
            'guest_email' => 'string',
            'guest_phone' => 'string',
            'address_street' => 'string',
            'address_city' => 'string',
            'address_state' => 'string',
            'address_postcode' => 'string',
            'address_country' => 'string',
            'address_apt' => 'string',
            'address_district' => 'string',
            'address_type' => 'string',
            'special_requests' => 'string'
        ]);

        // Validate data
        if (empty($data['check_in']) || empty($data['check_out']) || 
            empty($data['room_quantity']) || empty($data['guests']) || 
            empty($data['guest_name']) || empty($data['guest_email']) || 
            empty($data['guest_phone']) || empty($data['address_street']) ||
            empty($data['address_city']) || empty($data['address_state']) ||
            empty($data['address_postcode']) || empty($data['address_country'])) {
            Factory::getApplication()->enqueueMessage('All required fields must be filled', 'error');
            return ['success' => false];
        }

        try {
            $db = Factory::getDbo();

            // Generate unique booking number using timestamp
            $timestamp = time();
            $bookingNumber = 'WL' . date('Ymd', $timestamp) . $timestamp . rand(100, 999);
            
            // Calculate stay duration
            $checkIn = new Date($data['check_in']);
            $checkOut = new Date($data['check_out']);
            $nights = max(1, floor(($checkOut->toUnix() - $checkIn->toUnix()) / (60 * 60 * 24)));

            // Calculate total price and insert one booking record with multiple rooms
            $totalPrice = 0;
            $validRooms = 0;
            $roomsBooked = [];
            
            // Get the room data from the database
            $allRooms = $this->getRooms();
            $roomsById = [];
            foreach ($allRooms as $room) {
                $roomsById[$room->title] = $room;
            }
            
            // First, calculate the total price and validate rooms
            foreach ($data['room_quantity'] as $roomTitle => $quantity) {
                // Skip if quantity is zero or negative
                if ((int)$quantity <= 0) {
                    continue;
                }
                
                // Find the room by title
                if (!isset($roomsById[$roomTitle])) {
                    // Skip invalid rooms instead of throwing exception
                    Factory::getApplication()->enqueueMessage('Room "' . $roomTitle . '" not found, skipping', 'warning');
                    continue;
                }
                
                $room = $roomsById[$roomTitle];
                $validRooms++;
                $roomsBooked[$roomTitle] = (int)$quantity;

                // Calculate total price for the room type
                $roomTotalPrice = $room->price * $nights * (int)$quantity;
                $totalPrice += $roomTotalPrice;
            }
            
            // Check if any valid rooms were processed
            if ($validRooms === 0) {
                throw new Exception('No valid rooms were selected');
            }
            
            // Get the first room id for the booking record
            // (In a real-world scenario, you'd store room-booking relationships in a junction table)
            $firstRoomTitle = array_key_first($roomsBooked);
            $firstRoomId = $roomsById[$firstRoomTitle]->id;
            
            // Now create a single booking record
            $booking = (object)[
                'booking_number' => $bookingNumber,
                'room_id' => $firstRoomId, // Use the first room ID (we'll store multiple rooms in a separate table ideally)
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'guest_name' => $data['guest_name'],
                'guest_email' => $data['guest_email'],
                'guest_phone' => $data['guest_phone'],
                'address_street' => $data['address_street'],
                'address_city' => $data['address_city'],
                'address_state' => $data['address_state'],
                'address_postcode' => $data['address_postcode'],
                'address_country' => $data['address_country'],
                'address_apt' => $data['address_apt'] ?? null,
                'address_district' => $data['address_district'] ?? null,
                'address_type' => $data['address_type'] ?? 'home',
                'num_adults' => $data['guests'],
                'num_children' => $input->getInt('num_children', 0),
                'children_ages' => json_encode($input->get('children_ages', [], 'array')),
                'num_rooms' => array_sum($roomsBooked),
                'special_requests' => $data['special_requests'],
                'total_price' => $totalPrice,
                'booking_status' => 'confirmed',
                'payment_status' => 'pending',
                'created' => (new Date())->toSql()
            ];

            // Insert the booking
            $result = $db->insertObject('#__whiteleaf_bookings', $booking);
            
            if (!$result) {
                throw new Exception('Failed to insert booking record: ' . $db->getErrorMsg());
            }

            // Return success with booking data
            return [
                'success' => true,
                'data' => [
                    'booking_number' => $bookingNumber,
                    'total_price' => $totalPrice,
                    'rooms' => $roomsBooked
                ]
            ];

        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage('Booking failed: ' . $e->getMessage(), 'error');
            return ['success' => false];
        }
    }
}