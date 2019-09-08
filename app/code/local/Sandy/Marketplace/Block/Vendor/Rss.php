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
 * Vendor RSS block
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Vendor_Rss extends Mage_Rss_Block_Abstract
{
    /**
     * Cache tag constant for feed reviews
     *
     * @var string
     */
    const CACHE_TAG = 'block_html_marketplace_vendor_rss';

    /**
     * constructor
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->setCacheTags(array(self::CACHE_TAG));
        /*
         * setting cache to save the rss for 10 minutes
         */
        $this->setCacheKey('sandy_marketplace_vendor_rss');
        $this->setCacheLifetime(600);
    }

    /**
     * toHtml method
     *
     * @access protected
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _toHtml()
    {
        $url    = Mage::helper('sandy_marketplace/vendor')->getVendorsUrl();
        $title  = Mage::helper('sandy_marketplace')->__('Vendors');
        $rssObj = Mage::getModel('rss/rss');
        $data  = array(
            'title'       => $title,
            'description' => $title,
            'link'        => $url,
            'charset'     => 'UTF-8',
        );
        $rssObj->_addHeader($data);
        $collection = Mage::getModel('sandy_marketplace/vendor')->getCollection()
            ->addFieldToFilter('status', 1)
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('in_rss', 1)
            ->setOrder('created_at');
        $collection->load();
        foreach ($collection as $item) {
            $description = '<p>';
            $description .= '<div>'.
                Mage::helper('sandy_marketplace')->__('Shop URL').': 
                '.$item->getShopurl().
                '</div>';
            $description .= '<div>'.
                Mage::helper('sandy_marketplace')->__('Phone').': 
                '.$item->getPhone().
                '</div>';
            $description .= '<div>'.Mage::helper('sandy_marketplace')->__("Gender").':'.(($item->getGender() == 1) ? Mage::helper('sandy_marketplace')->__('Yes') : Mage::helper('sandy_marketplace')->__('No')).'</div>';
            $description .= '</p>';
            $data = array(
                'title'       => $item->getShopurl(),
                'link'        => $item->getVendorUrl(),
                'description' => $description
            );
            $rssObj->_addEntry($data);
        }
        return $rssObj->createRssXml();
    }
}
