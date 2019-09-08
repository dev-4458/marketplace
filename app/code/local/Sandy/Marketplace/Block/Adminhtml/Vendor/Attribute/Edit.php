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
 * Vendor attribute edit block
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Vendor_Attribute_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_objectId = 'attribute_id';
        $this->_controller = 'adminhtml_vendor_attribute';
        $this->_blockGroup = 'sandy_marketplace';

        parent::__construct();
        $this->_addButton(
            'save_and_edit_button',
            array(
                'label'     => Mage::helper('sandy_marketplace')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save'
            ),
            100
        );
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('sandy_marketplace')->__('Save Vendor Attribute')
        );
        $this->_updateButton('save', 'onclick', 'saveAttribute()');

        if (!Mage::registry('entity_attribute')->getIsUserDefined()) {
            $this->_removeButton('delete');
        } else {
            $this->_updateButton(
                'delete',
                'label',
                Mage::helper('sandy_marketplace')->__('Delete Vendor Attribute')
            );
        }
    }

    /**
     * get the header text for the form
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('entity_attribute')->getId()) {
            $frontendLabel = Mage::registry('entity_attribute')->getFrontendLabel();
            if (is_array($frontendLabel)) {
                $frontendLabel = $frontendLabel[0];
            }
            return Mage::helper('sandy_marketplace')->__('Edit Vendor Attribute "%s"', $this->escapeHtml($frontendLabel));
        } else {
            return Mage::helper('sandy_marketplace')->__('New Vendor Attribute');
        }
    }

    /**
     * get validation url for form
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

    /**
     * get save url for form
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/'.$this->_controller.'/save', array('_current'=>true, 'back'=>null));
    }
}
