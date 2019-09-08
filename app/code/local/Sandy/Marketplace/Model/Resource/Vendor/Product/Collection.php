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
 * Vendor - product relation resource model collection
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Resource_Vendor_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return Sandy_Marketplace_Model_Resource_Vendor_Product_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('sandy_marketplace/vendor_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add vendor filter
     *
     * @access public
     * @param Sandy_Marketplace_Model_Vendor | int $vendor
     * @return Sandy_Marketplace_Model_Resource_Vendor_Product_Collection
     * @author Ultimate Module Creator
     */
    public function addVendorFilter($vendor)
    {
        if ($vendor instanceof Sandy_Marketplace_Model_Vendor) {
            $vendor = $vendor->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.vendor_id = ?', $vendor);
        return $this;
    }
}
