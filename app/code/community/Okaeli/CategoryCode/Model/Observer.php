<?php
/**
 * Okaeli_CategoryCode  Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE Version 3
 * that is bundled with this package in the file LICENSE
 *
 * @category Okaeli
 * @package Okaeli_CategoryCode
 * @copyright  Copyright (c)  2017 Julien Loizelet
 * @author     Julien Loizelet <julienloizelet@okaeli.com>
 * @license    GNU GENERAL PUBLIC LICENSE Version 3
 *
 */

/**
 *
 * @category Okaeli
 * @package  Okaeli_CategoryCode
 * @module   CategoryCode
 * @author   Julien Loizelet <julienloizelet@okaeli.com>
 *
 */
class Okaeli_CategoryCode_Model_Observer extends Mage_Catalog_Model_Observer
{

    /**
     * @var Okaeli_CategoryCode_Helper_Data
     */
    protected $_helper;

    /**
     * Add handles to manage specific category
     *
     * @param Varien_Event_Observer $observer
     */
    public function manageHandles(Varien_Event_Observer $observer)
    {

        $action = $observer->getEvent()->getAction();
        $categoryPage = ($action->getFullActionName() == 'catalog_category_view');

        // We add handles only in a category page
        if (!$categoryPage) {
            return;
        }

        $category = Mage::registry('current_category');
        if (!is_object($category)) {
            return;
        }

        $helper = $this->_getHelper();
        if ($helper->isFrontendLayoutUpdateEnabled()) {
            $categoryCode = $category->getOkaeliCategoryCode();

            // We add handle with category code
            if (!empty($categoryCode)) {
                $update = $observer->getEvent()->getLayout()->getUpdate();
                $update->addHandle('catalog_category_code_' . strtoupper($categoryCode));
            }
        }
    }

    /**
     * Disable category code input in admin edition
     * @param Varien_Event_Observer $observer
     */
    public function disableCategoryCodeAdminEdit(Varien_Event_Observer $observer)
    {
        $form = $observer->getEvent()->getForm();
        if ($form->getElement('okaeli_category_code')) {
            $helper = $this->_getHelper();
            if ($helper->isAdminInputDisabled()) {
                $form->getElement('okaeli_category_code')->setDisabled('disabled');
            }
        }
    }

    /**
     * Get CategoryCode Helper
     * @return Okaeli_CategoryCode_Helper_Data
     */
    protected function _getHelper()
    {

        if ($this->_helper === null) {
            $this->_helper = Mage::helper('okaeli_categorycode');
        }

        return $this->_helper;
    }
}
