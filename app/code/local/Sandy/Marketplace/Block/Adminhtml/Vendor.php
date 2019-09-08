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
 * Vendor admin block
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Vendor extends Mage_Adminhtml_Block_Widget_Grid_Container
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
        $this->_controller         = 'adminhtml_vendor';
        $this->_blockGroup         = 'sandy_marketplace';
        parent::__construct();
        $this->_headerText         = Mage::helper('sandy_marketplace')->__('Vendor');
        $this->_updateButton('add', 'label', Mage::helper('sandy_marketplace')->__('Add Vendor'));

        $this->setTemplate('sandy_marketplace/grid.phtml');
    }
}
