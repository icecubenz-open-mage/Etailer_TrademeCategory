<?php

require_once 'abstract.php';

use League\Csv\EncloseField;
use League\Csv\Writer;

class Etailer_Shell_TrademeCategoryOmnivoreExport extends Mage_Shell_Abstract
{
    protected $_map = array();
    protected const HEADERS = array('sku', 'categoryid', 'keyword', 'marketcat1', 'marketcat2', 'marketcat3');

    /**
     * Run the command
     *
     * @return Etailer_Shell_TrademeCategoryOmnivoreExport
     */
    public function run()
    {
        $rootCatId = $this->getArg('catid');

        if ($rootCatId) {
            $this->_getMapping($rootCatId);

            $csv = Writer::createFromString();
            EncloseField::addTo($csv, "\t\x1f"); //adding the stream filter to force enclosure
            $csv->insertOne(self::HEADERS);
            $csv->insertAll($this->_map);
            echo $csv->getContent();

            return $this;
        }

        // if nothing called, just do the help
        echo $this->usageHelp();

        return $this;
    }

    protected function _getMapping($categoryId)
    {
        $_category = Mage::getModel('catalog/category')->load($categoryId);
        if ($_category->getId()) {
            if ($_category->getData('trademe_category_number')) {
                // same map as the headers
                $this->_map[] = array(
                    '', // sku
                    $_category->getId(), //categoryid
                    '', // keyword
                    $_category->getData('trademe_category_number'), // marketcat1
                    '', // marketcat2
                    '' // marketcat3
                );
            }

            $_categories = $_category->getChildrenCategoriesWithInactive();
            foreach ($_categories as $_cat) {
                $this->_getMapping($_cat->getId());
            }
        }

        return $this;
    }

    /**
     * Retrieve Usage Help Message
     *
     * @return string
     */
    public function usageHelp()
    {
        $defaultExclude = self::DEFAULT_EXCLUDE;

        return <<<USAGE
Exports Trade Me Category Mapping CSV for Omnivore / TradeRunner.

Usage:  php -f trademe_category_omnivore_export.php -- [options]

  --catid <id>             Target Magento category ID to export (base of the tree)

  help                     This help


USAGE;
    }
}

// run the shell script
$shell = new Etailer_Shell_TrademeCategoryOmnivoreExport();
$shell->run();
