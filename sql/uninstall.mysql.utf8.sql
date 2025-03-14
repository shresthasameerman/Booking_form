-- sql/uninstall.mysql.utf8.sql
-- First disable foreign key checks to avoid constraint errors
SET FOREIGN_KEY_CHECKS=0;

-- Drop tables in reverse order of dependencies
DROP TABLE IF EXISTS `#__whiteleaf_bookings`;
DROP TABLE IF EXISTS `#__whiteleaf_room_availability`;
DROP TABLE IF EXISTS `#__whiteleaf_rooms`;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;