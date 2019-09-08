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
 * Admin search model
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Adminhtml_Search_Vendor extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Sandy_Marketplace_Model_Adminhtml_Search_Vendor
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('sandy_marketplace/vendor_collection')
            ->addAttributeToFilter('shopurl', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $vendor) {
            $arr[] = array(
                'id'          => 'vendor/1/'.$vendor->getId(),
                'type'        => Mage::helper('sandy_marketplace')->__('Vendor'),
                'name'        => $vendor->getShopurl(),
                'description' => $vendor->getShopurl(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/marketplace_vendor/edit',
                    array('id'=>$vendor->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
