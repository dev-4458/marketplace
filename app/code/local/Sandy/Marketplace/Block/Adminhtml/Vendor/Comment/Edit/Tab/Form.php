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
 * Vendor comment edit form tab
 *
 * @category    Sandy
 * @package     Sandy_Marketplace
 * @author      Sandy Infocom
 */
class Sandy_Marketplace_Block_Adminhtml_Vendor_Comment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Marketplace_Vendor_Block_Adminhtml_Vendor_Comment_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $vendor = Mage::registry('current_vendor');
        $comment    = Mage::registry('current_comment');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('comment_');
        $form->setFieldNameSuffix('comment');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'comment_form',
            array('legend'=>Mage::helper('sandy_marketplace')->__('Comment'))
        );
        $fieldset->addField(
            'vendor_id',
            'hidden',
            array(
                'name'  => 'vendor_id',
                'after_element_html' => '<a href="'.
                    Mage::helper('adminhtml')->getUrl(
                        'adminhtml/marketplace_vendor/edit',
                        array(
                            'id'=>$vendor->getId()
                        )
                    ).
                    '" target="_blank">'.
                    Mage::helper('sandy_marketplace')->__('Vendor').
                    ' : '.$vendor->getShopurl().'</a>'
            )
        );
        $fieldset->addField(
            'title',
            'text',
            array(
                'label'    => Mage::helper('sandy_marketplace')->__('Title'),
                'name'     => 'title',
                'required' => true,
                'class'    => 'required-entry',
            )
        );
        $fieldset->addField(
            'comment',
            'textarea',
            array(
                'label'    => Mage::helper('sandy_marketplace')->__('Comment'),
                'name'     => 'comment',
                'required' => true,
                'class'    => 'required-entry',
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'    => Mage::helper('sandy_marketplace')->__('Status'),
                'name'     => 'status',
                'required' => true,
                'class'    => 'required-entry',
                'values'   => array(
                    array(
                        'value' => Sandy_Marketplace_Model_Vendor_Comment::STATUS_PENDING,
                        'label' => Mage::helper('sandy_marketplace')->__('Pending'),
                    ),
                    array(
                        'value' => Sandy_Marketplace_Model_Vendor_Comment::STATUS_APPROVED,
                        'label' => Mage::helper('sandy_marketplace')->__('Approved'),
                    ),
                    array(
                        'value' => Sandy_Marketplace_Model_Vendor_Comment::STATUS_REJECTED,
                        'label' => Mage::helper('sandy_marketplace')->__('Rejected'),
                    ),
                ),
            )
        );
        $configuration = array(
             'label' => Mage::helper('sandy_marketplace')->__('Poster name'),
             'name'  => 'name',
             'required'  => true,
             'class' => 'required-entry',
        );
        if ($comment->getCustomerId()) {
            $configuration['after_element_html'] = '<a href="'.
                Mage::helper('adminhtml')->getUrl(
                    'adminhtml/customer/edit',
                    array(
                        'id'=>$comment->getCustomerId()
                    )
                ).
                '" target="_blank">'.
                Mage::helper('sandy_marketplace')->__('Customer profile').'</a>';
        }
        $fieldset->addField('name', 'text', $configuration);
        $fieldset->addField(
            'email',
            'text',
            array(
                'label' => Mage::helper('sandy_marketplace')->__('Poster e-mail'),
                'name'  => 'email',
                'required'  => true,
                'class' => 'required-entry',
            )
        );
        $fieldset->addField(
            'customer_id',
            'hidden',
            array(
                'name'  => 'customer_id',
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_comment')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getComment()->getData());
        return parent::_prepareForm();
    }

    /**
     * get the current comment
     *
     * @access public
     * @return Sandy_Marketplace_Model_Vendor_Comment
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
