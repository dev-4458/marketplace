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
 * Vendor comment admin edit form
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Vendor_Comment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_controller = 'adminhtml_vendor_comment';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('sandy_marketplace')->__('Save Vendor comment')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('sandy_marketplace')->__('Delete Vendor comment')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'        => Mage::helper('sandy_marketplace')->__('Save And Continue Edit'),
                'onclick'    => 'saveAndContinueEdit()',
                'class'        => 'save',
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
        if (Mage::registry('comment_data') && Mage::registry('comment_data')->getId()) {
            return Mage::helper('sandy_marketplace')->__(
                "Edit Vendor comment '%s'",
                $this->escapeHtml(Mage::registry('comment_data')->getTitle())
            );
        }
        return '';
    }
}
