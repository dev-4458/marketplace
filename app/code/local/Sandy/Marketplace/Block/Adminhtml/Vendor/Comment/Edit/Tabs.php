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
 * Vendor comment admin edit tabs
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Vendor_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('vendor_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('sandy_marketplace')->__('Vendor Comment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Sandy_Marketplace_Block_Adminhtml_Vendor_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_vendor_comment',
            array(
                'label'   => Mage::helper('sandy_marketplace')->__('Vendor comment'),
                'title'   => Mage::helper('sandy_marketplace')->__('Vendor comment'),
                'content' => $this->getLayout()->createBlock(
                    'sandy_marketplace/adminhtml_vendor_comment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_vendor_comment',
                array(
                    'label'   => Mage::helper('sandy_marketplace')->__('Store views'),
                    'title'   => Mage::helper('sandy_marketplace')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'sandy_marketplace/adminhtml_vendor_comment_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve comment
     *
     * @access public
     * @return Sandy_Marketplace_Model_Vendor_Comment
     * @author Ultimate Module Creator
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
