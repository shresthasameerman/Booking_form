<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

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
            // Log error message and return an empty array
            Factory::getApplication()->enqueueMessage('Error fetching rooms: ' . $e->getMessage(), 'error');
            return [];
        }
    }
}