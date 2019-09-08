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
 * Vendor attribute API V2 model
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Vendor_Attribute_Api_V2 extends Sandy_Marketplace_Model_Vendor_Attribute_Api
{
    /**
     * Create new vendor attribute
     *
     * @access public
     * @param array $data input data
     * @return integer
     * @author Ultimate Module Creator
     */
    public function create($data)
    {
        $helper = Mage::helper('api');
        $helper->v2AssociativeArrayUnpacker($data);
        Mage::helper('api')->toArray($data);
        return parent::create($data);
    }

    /**
     * Update product attribute
     *
     * @access public
     * @param string|integer $attribute attribute code or ID
     * @param array $data
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function update($attribute, $data)
    {
        $helper = Mage::helper('api');
        $helper->v2AssociativeArrayUnpacker($data);
        Mage::helper('api')->toArray($data);
        return parent::update($attribute, $data);
    }

    /**
     * Add option to select or multiselect attribute
     *
     * @access public
     * @param  integer|string $attribute attribute ID or code
     * @param  array $data
     * @return bool
     * @author Ultimate Module Creator
     */
    public function addOption($attribute, $data)
    {
        Mage::helper('api')->toArray($data);
        return parent::addOption($attribute, $data);
    }

    /**
     * Get full information about attribute with list of options
     *
     * @access public
     * @param integer|string $attribute attribute ID or code
     * @return array
     * @author Ultimate Module Creator
     */
    public function info($attribute)
    {
        $result = parent::info($attribute);
        if (!empty($result['additional_fields'])) {
            $keys = array_keys($result['additional_fields']);
            foreach ($keys as $key) {
                $result['additional_fields'][] = array(
                    'key'   => $key,
                    'value' => $result['additional_fields'][$key]
                );
                unset($result['additional_fields'][$key]);
            }
        }
        return $result;
    }
}
