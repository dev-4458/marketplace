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
 * Adminhtml vendor attribute edit page tabs
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Vendor_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('vendor_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('sandy_marketplace')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return Sandy_Marketplace_Adminhtml_Vendor_Attribute_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('sandy_marketplace')->__('Properties'),
                'title'     => Mage::helper('sandy_marketplace')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'sandy_marketplace/adminhtml_vendor_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('sandy_marketplace')->__('Manage Label / Options'),
                'title'     => Mage::helper('sandy_marketplace')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'sandy_marketplace/adminhtml_vendor_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}
