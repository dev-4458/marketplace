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
 * Vendor attribute collection model
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Resource_Vendor_Attribute_Collection extends Mage_Eav_Model_Resource_Entity_Attribute_Collection
{
    /**
     * init attribute select
     *
     * @access protected
     * @return Sandy_Marketplace_Model_Resource_Vendor_Attribute_Collection
     * @author Ultimate Module Creator
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()))
            ->where(
                'main_table.entity_type_id=?',
                Mage::getModel('eav/entity')->setType('sandy_marketplace_vendor')->getTypeId()
            )
            ->join(
                array('additional_table' => $this->getTable('sandy_marketplace/eav_attribute')),
                'additional_table.attribute_id=main_table.attribute_id'
            );
        return $this;
    }

    /**
     * set entity type filter
     *
     * @access public
     * @param string $typeId
     * @return Sandy_Marketplace_Model_Resource_Vendor_Attribute_Collection
     * @author Ultimate Module Creator
     */
    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }

    /**
     * Specify filter by "is_visible" field
     *
     * @access public
     * @return Sandy_Marketplace_Model_Resource_Vendor_Attribute_Collection
     * @author Ultimate Module Creator
     */
    public function addVisibleFilter()
    {
        return $this->addFieldToFilter('additional_table.is_visible', 1);
    }

    /**
     * Specify filter by "is_editable" field
     *
     * @access public
     * @return Sandy_Marketplace_Model_Resource_Vendor_Attribute_Collection
     * @author Ultimate Module Creator
     */
    public function addEditableFilter()
    {
        return $this->addFieldToFilter('additional_table.is_editable', 1);
    }
}
