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
class Okaeli_CategoryCode_Helper_Config extends Mage_Core_Helper_Abstract
{

    const XML_PATH_DISABLE_ADMIN_INPUT = 'okaeli_categorycode/admin/disable_input';
    const XML_PATH_FRONTEND_LAYOUT_UPDATE = 'okaeli_categorycode/frontend/enable_layout_update';

    /**
     * @var bool
     */
    protected $_disableAdminInput;
    /**
     * @var bool
     */
    protected $_frontendLayoutUpdate;

    /**
     * Check config for code edition
     * @return bool
     */
    public function isAdminInputDisabled()
    {
        if ($this->_disableAdminInput === null) {
            $disabled = Mage::getStoreConfig(self::XML_PATH_DISABLE_ADMIN_INPUT);
            $this->_disableAdminInput = $disabled;
        }

        return $this->_disableAdminInput;
    }

    public function isFrontendLayoutUpdateEnabled()
    {
        if ($this->_frontendLayoutUpdate === null) {
            $enabled = Mage::getStoreConfig(self::XML_PATH_FRONTEND_LAYOUT_UPDATE);
            $this->_frontendLayoutUpdate = $enabled;
        }

        return $this->_frontendLayoutUpdate;
    }

}
