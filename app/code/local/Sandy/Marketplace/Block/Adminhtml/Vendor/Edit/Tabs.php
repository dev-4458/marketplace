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
 * Vendor admin edit tabs
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Vendor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('vendor_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('sandy_marketplace')->__('Vendor Information'));
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Adminhtml_Vendor_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $vendor = $this->getVendor();
        $entity = Mage::getModel('eav/entity_type')
            ->load('sandy_marketplace_vendor', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->addFieldToFilter(
            'attribute_code',
            array(
                'nin' => array('meta_title', 'meta_description', 'meta_keywords')
            )
        );
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('sandy_marketplace')->__('Vendor Information'),
                'content' => $this->getLayout()->createBlock(
                    'sandy_marketplace/adminhtml_vendor_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->toHtml(),
            )
        );
        $seoAttributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId())
            ->addFieldToFilter(
                'attribute_code',
                array(
                    'in' => array('meta_title', 'meta_description', 'meta_keywords')
                )
            );
        $seoAttributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'meta',
            array(
                'label'   => Mage::helper('sandy_marketplace')->__('Meta'),
                'title'   => Mage::helper('sandy_marketplace')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'sandy_marketplace/adminhtml_vendor_edit_tab_attributes'
                )
                ->setAttributes($seoAttributes)
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('sandy_marketplace')->__('Associated products'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve vendor entity
     *
     * @access public
     * @return Sandy_Marketplace_Model_Vendor
     * @author Ultimate Module Creator
     */
    public function getVendor()
    {
        return Mage::registry('current_vendor');
    }
}
