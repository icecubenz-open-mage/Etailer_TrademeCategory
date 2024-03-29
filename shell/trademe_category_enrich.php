<?php

require_once 'abstract.php';

class Etailer_Shell_TrademeCategoryEnrich extends Mage_Shell_Abstract
{
    protected const TRADEME_JSON_URL = 'https://api.trademe.co.nz/v1/Categories.json';
    protected $_trademeCategories;

    /**
     * Run the command
     *
     * @return Etailer_Shell_TrademeCategoryEnrich
     */
    public function run()
    {
        ini_set('display_errors', 1);
        ini_set('memory_limit', '16000M');
        error_reporting(E_ALL);
        set_time_limit(36000);

        $trademeJson = $this->getArg('tmjson') ?: self::TRADEME_JSON_URL;

        if ($trademeJson) {
            $this->_trademeCategories = $this->_getJsonFromUrl($trademeJson);

            $collection = Mage::getSingleton('index/indexer')->getProcessesCollection();
            foreach ($collection as $process) {
                $process->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
            }

            foreach ($this->_trademeCategories['Subcategories'] as $trademeCategory) {
                $this->_enrichCategories($trademeCategory);
            }

            foreach ($collection as $process) {
                $process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
                $process->reindexEverything();
            }

            return $this;
        }

        // if nothing called, just do the help
        echo $this->usageHelp();

        return $this;
    }

    protected function _enrichCategories($trademeCategory)
    {
        $categoryCollection = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('trademe_category_name')
            ->addAttributeToSelect('trademe_category_number')
            ->addAttributeToSelect('trademe_category_path')
            ->addAttributeToSelect('trademe_category_aob')
            ->addAttributeToSelect('trademe_category_is_leaf')
            ->addAttributeToFilter('trademe_category_number', $trademeCategory['Number']);
        foreach ($categoryCollection as $_category) {
            // $_category = Mage::getModel('catalog/category')->load($_category->getId());
            $_category->setData('trademe_category_name', $trademeCategory['Name'])
                ->setData('trademe_category_path', $trademeCategory['Path'])
                ->setData('trademe_category_aob', $trademeCategory['AreaOfBusiness'])
                ->setData('trademe_category_is_leaf', $trademeCategory['IsLeaf'])
                ->save();

            if ((bool) !$trademeCategory['IsLeaf']) {
                $_products = Mage::getResourceModel('catalog/product_collection')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->addCategoryFilter($_category);

                foreach ($_products as $_product) {
                    Mage::getSingleton('catalog/category_api')->removeProduct($_category->getId(), $_product->getId());
                }
            }
        }

        foreach ($trademeCategory['Subcategories'] as $trademeSubcategory) {
            $this->_enrichCategories($trademeSubcategory);
        }

        return $this;
    }

    /**
     * parse JSON from a remote URL
     *
     * @return void
     */
    protected function _getJsonFromUrl($jsonUrl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $jsonUrl);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json'
            )
        );
        $result = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            $result = '{}';
        }
        curl_close($ch);
        return Zend_Json::decode($result);
    }

    /**
     * Retrieve Usage Help Message
     *
     * @return string
     */
    public function usageHelp()
    {
        return <<<USAGE
Enriches existing Trade Me categories.

Usage:  php -f trademe_category_enrich.php -- [options]

  --tmjson <url>           Trade Me JSON (optional).

  help                     This help


USAGE;
    }
}

// run the shell script
$shell = new Etailer_Shell_TrademeCategoryEnrich();
$shell->run();
