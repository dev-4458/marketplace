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
 * Vendor REST API admin handler
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Model_Api2_Vendor_Rest_Admin_V1 extends Sandy_Marketplace_Model_Api2_Vendor_Rest
{

    /**
     * Remove specified keys from associative or indexed array
     *
     * @access protected
     * @param array $array
     * @param array $keys
     * @param bool $dropOrigKeys if true - return array as indexed array
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _filterOutArrayKeys(array $array, array $keys, $dropOrigKeys = false) {
        $isIndexedArray = is_array(reset($array));
        if ($isIndexedArray) {
            foreach ($array as &$value) {
                if (is_array($value)) {
                    $value = array_diff_key($value, array_flip($keys));
                }
            }
            if ($dropOrigKeys) {
                $array = array_values($array);
            }
            unset($value);
        } else {
            $array = array_diff_key($array, array_flip($keys));
        }
        return $array;
    }

    /**
     * Retrieve list of vendors
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _retrieveCollection() {
        $collection = Mage::getResourceModel('sandy_marketplace/vendor_collection')->addAttributeToSelect('*');
        $collection->setStoreId($this->_getStore()->getId());
        $entityOnlyAttributes = $this->getEntityOnlyAttributes($this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ);
        $availableAttributes = array_keys($this->getAvailableAttributes($this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ));
        $this->_applyCollectionModifiers($collection);
        $vendors = $collection->load();

        foreach ($vendors as $vendor) {
            $this->_setVendor($vendor);
            $this->_prepareVendorForResponse($vendor);
        }
        $vendorsArray = $vendors->toArray();
        return $vendorsArray;
    }

    /**
     * Delete vendor by its ID
     *
     * @access protected
     * @throws Mage_Api2_Exception
     * @author Ultimate Module Creator
     */
    protected function _delete() {
        $vendor = $this->_getVendor();
        try {
            $vendor->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->_critical(self::RESOURCE_INTERNAL_ERROR);
        }
    }

    /**
     * Create vendor
     *
     * @access protected
     * @param array $data
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _create(array $data) {
        $vendor = Mage::getModel('sandy_marketplace/vendor')->setData($data);
        try {
            $vendor->save();
        }
        catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->_critical(self::RESOURCE_UNKNOWN_ERROR);
        }
        return $this->_getLocation($vendor->getId());
    }

    /**
     * Update vendor by its ID
     *
     * @access protected
     * @param array $data
     * @author Ultimate Module Creator
     */
    protected function _update(array $data) {
        $vendor = $this->_getVendor();
        $vendor->addData($data);
        try {
            $vendor->save();
        } catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->_critical(self::RESOURCE_UNKNOWN_ERROR);
        }
    }

    /**
     * Set additional data before vendor save
     *
     * @access protected
     * @param Sandy_Marketplace_Model_Vendor $entity
     * @param array $vendorData
     * @author Ultimate Module Creator
     */
    protected function _prepareDataForSave($product, $productData) {
        //add your data processing algorithm here if needed
    }
}