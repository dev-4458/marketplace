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
 * Vendor tab on product edit form
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Catalog_Product_Edit_Tab_Vendor extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Ultimate Module Creator
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('vendor_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_vendors'=>1));
        }
    }

    /**
     * prepare the vendor collection
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Adminhtml_Catalog_Product_Edit_Tab_Vendor
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('sandy_marketplace/vendor_collection')->addAttributeToSelect('shopurl');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('sandy_marketplace/vendor_product')),
            'related.vendor_id=e.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Adminhtml_Catalog_Product_Edit_Tab_Vendor
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Adminhtml_Catalog_Product_Edit_Tab_Vendor
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_vendors',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_vendors',
                'values'=> $this->_getSelectedVendors(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'shopurl',
            array(
                'header' => Mage::helper('sandy_marketplace')->__('Shop URL'),
                'align'  => 'left',
                'index'  => 'shopurl',
                'renderer' => 'sandy_marketplace/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/marketplace_vendor/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('sandy_marketplace')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected vendors
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedVendors()
    {
        $vendors = $this->getProductVendors();
        if (!is_array($vendors)) {
            $vendors = array_keys($this->getSelectedVendors());
        }
        return $vendors;
    }

    /**
     * Retrieve selected vendors
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedVendors()
    {
        $vendors = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('sandy_marketplace/product')->getSelectedVendors(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $vendor) {
            $vendors[$vendor->getId()] = array('position' => $vendor->getPosition());
        }
        return $vendors;
    }

    /**
     * get row url
     *
     * @access public
     * @param Sandy_Marketplace_Model_Vendor
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/vendorsGrid',
            array(
                'id'=>$this->getProduct()->getId()
            )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Ultimate Module Creator
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return Sandy_Marketplace_Block_Adminhtml_Catalog_Product_Edit_Tab_Vendor
     * @author Ultimate Module Creator
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_vendors') {
            $vendorIds = $this->_getSelectedVendors();
            if (empty($vendorIds)) {
                $vendorIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$vendorIds));
            } else {
                if ($vendorIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$vendorIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
