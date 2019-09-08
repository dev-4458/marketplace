<?php
/**
 * Sandy_Marketplace extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Sandy
 * @package        Sandy_Marketplace
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Vendor comment model
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Vendor_Comment_Api extends Mage_Api_Model_Resource_Abstract
{
    /**
     * get vendors comments
     *
     * @access public
     * @param mixed $filters
     * @return array
     * @author Ultimate Module Creator
     */
    public function items($filters = null)
    {
        $collection = Mage::getModel('sandy_marketplace/vendor_comment')->getCollection();
        $apiHelper = Mage::helper('api');
        $filters = $apiHelper->parseFilters($filters);
        try {
            foreach ($filters as $field => $value) {
                $collection->addFieldToFilter($field, $value);
            }
        } catch (Mage_Core_Exception $e) {
            $this->_fault('filters_invalid', $e->getMessage());
        }
        $result = array();
        foreach ($collection as $vendor) {
            $result[] = $vendor->getData();
        }
        return $result;
    }

    /**
     * update comment status
     *
     * @access public
     * @param mixed $filters
     * @return bool
     * @author Ultimate Module Creator
     */
    public function updateStatus($commentId, $status)
    {
        $comment = Mage::getModel('sandy_marketplace/vendor_comment')->load($commentId);
        if (!$comment->getId()) {
            $this->_fault('not_exists');
        }
        try {
            $comment->setStatus($status)->save();
        }
        catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return true;
    }
}
