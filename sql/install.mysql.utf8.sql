-- /modules/mod_whiteleaf_booking/sql/install.mysql.utf8.sql
-- Table for rooms
CREATE TABLE IF NOT EXISTS `#__whiteleaf_rooms` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`description` text,
`image` varchar(255) DEFAULT NULL, -- New column for storing image path
`price` decimal(10,2) NOT NULL,
`capacity` int(11) NOT NULL,
`published` tinyint(1) NOT NULL DEFAULT '1',
`created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- First clear existing data and reset auto-increment
DELETE FROM `#__whiteleaf_rooms`;
ALTER TABLE `#__whiteleaf_rooms` AUTO_INCREMENT = 1;

-- Insert sample data
INSERT INTO `#__whiteleaf_rooms` (`title`, `description`, `price`, `capacity`, `published`) VALUES
('Standard Room', 'Comfortable room with basic amenities', 100.00, 2, 1),
('Semi-Deluxe Room', 'Spacious room with premium amenities', 150.00, 2, 1),
('Deluxe Rooms', 'Luxury suite with separate living area', 250.00, 4, 1),
('Ultra Deluxe Rooms', 'Large room ideal for families', 200.00, 4, 1);

-- Table for room availability
CREATE TABLE IF NOT EXISTS `#__whiteleaf_room_availability` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`room_id` int(11) NOT NULL,
`date` date NOT NULL,
`available_rooms` int(11) NOT NULL DEFAULT 0,
`total_rooms` int(11) NOT NULL DEFAULT 0,
PRIMARY KEY (`id`),
UNIQUE KEY `room_date` (`room_id`,`date`),
CONSTRAINT `fk_room_availability_room` FOREIGN KEY (`room_id`)
REFERENCES `#__whiteleaf_rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for bookings
CREATE TABLE IF NOT EXISTS `#__whiteleaf_bookings` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`booking_number` varchar(50) NOT NULL,
`room_id` int(11) NOT NULL,
`check_in` date NOT NULL,
`check_out` date NOT NULL,
`guest_name` varchar(255) NOT NULL,
`guest_email` varchar(255) NOT NULL,
`guest_phone` varchar(50) NOT NULL,
`num_adults` int(11) NOT NULL DEFAULT 1,
`num_children` int(11) NOT NULL DEFAULT 0,
`children_ages` JSON DEFAULT NULL, -- New column for storing children's ages as JSON array
`num_rooms` int(11) NOT NULL DEFAULT 1,
`special_requests` text,
`total_price` decimal(10,2) NOT NULL,
`payment_status` enum('pending','paid','cancelled','refunded') NOT NULL DEFAULT 'pending',
`booking_status` enum('pending','confirmed','checked_in','checked_out','cancelled') NOT NULL DEFAULT 'pending',
`created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`modified` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `booking_number` (`booking_number`),
KEY `room_id` (`room_id`),
CONSTRAINT `fk_bookings_room` FOREIGN KEY (`room_id`)
REFERENCES `#__whiteleaf_rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
