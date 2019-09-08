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
 * Vendor admin edit form
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Vendor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'sandy_marketplace';
        $this->_controller = 'adminhtml_vendor';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('sandy_marketplace')->__('Save Vendor')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('sandy_marketplace')->__('Delete Vendor')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('sandy_marketplace')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_vendor') && Mage::registry('current_vendor')->getId()) {
            return Mage::helper('sandy_marketplace')->__(
                "Edit Vendor '%s'",
                $this->escapeHtml(Mage::registry('current_vendor')->getShopurl())
            );
        } else {
            return Mage::helper('sandy_marketplace')->__('Add Vendor');
        }
    }
}
