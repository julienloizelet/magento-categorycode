<?php

require_once 'abstract.php';

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
 * @copyright  Copyright (c)  2018 Julien Loizelet
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
class Mage_Shell_GenerateCategoryCodes extends Mage_Shell_Abstract
{

    /**
     * Displays critical message and exit.
     */
    protected function _fault($msg)
    {
        exit(PHP_EOL . $msg . PHP_EOL);
    }

    /**
     * Main script method that create category codes from url key
     */
    public function run()

    {
        $start = microtime(true);

        if (!$this->getArg('r')) {
            echo $this->usageHelp();
        } else {

            $force = $this->getArg('f');
            $unicity = $this->getArg('u');

            try {

                $categories = Mage::getModel('catalog/category')->getCollection()
                    ->addAttributeToSelect(array('okaeli_category_code', 'url_key'));

                foreach ($categories as $category) {

                    if (!$category->getOkaeliCategoryCode() || $force) {
                        if ($urlKey = $category->getUrlKey()) {
                            $code = $urlKey;
                            if ($unicity) {
                                $code = $this->_findAvailableCode($code);
                            }

                            $category->setOkaeliCategoryCode($code);
                            $category->getResource()->saveAttribute($category, 'okaeli_category_code');
                        }
                    }
                }
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_fault($e->getMessage());
            }
        }

        $end = microtime(true);
        $time = $end - $start;
        echo PHP_EOL;
        echo 'Script Start: ' . date('H:i:s', $start) . PHP_EOL;
        echo 'Script End: ' . date('H:i:s', $end) . PHP_EOL;
        echo 'Duration: ' . number_format($time, 3) . ' sec' . PHP_EOL;
    }

    /**
     * Return an available code
     * @param string $code
     * @return string
     */
    protected function _findAvailableCode($code)
    {
        $i = 0;
        do {
            $newCode = ($i == 0) ? $code : $code . '-' . $i;
            /** @var Mage_Catalog_Model_Resource_Category_Collection $category */
            $categories = Mage::getModel('catalog/category')->getCollection();
            $category = $categories->addAttributeToSelect(array('okaeli_category_code'))
                ->addAttributeToFilter('okaeli_category_code', array('eq' => $newCode))
                ->setPageSize(1)
                ->getFirstItem();
            unset($categories);
            $i++;
        } while (is_object($category) && $category->getId());

        return $newCode;
    }

    /**
     * List of available script options.
     *
     * @return string
     */
    public function usageHelp()
    {
        return <<< USAGE
Usage:  php -f generate_category_codes.php -- -r

  -f            Force code creation even if already exists
  -h            Short alias for help
  -r            Mandatory to ensure you have read this help
  -u            Ensure that code will be unique
  help          This help

  ex : php -f generate_category_codes.php -- -r -u
USAGE;
    }
}

if (php_sapi_name() != 'cli') {
    exit('Run it from cli.');
}

$shell = new Mage_Shell_GenerateCategoryCodes();
$shell->run();
