<?php

require_once 'abstract.php';

class Etailer_Shell_TrademeCategory extends Mage_Shell_Abstract
{
    /**
     * Run the command
     *
     * @return Etailer_Shell_TrademeCategory
     */
    public function run()
    {
        $rootCatId   = $this->getArg('catid');
        $trademeJson = $this->getArg('tmjson');
        $active      = (bool) $this->getArg('active');
        $menu        = (bool) $this->getArg('menu');
        $exclude     = $this->getArg('exclude') ?: '0001-,0350-,5000-';
        $exclude     = explode(',', $exclude);

        if ($rootCatId && $trademeJson) {
            $trademeCategories = $this->_getJsonFromUrl($trademeJson);

            foreach ($trademeCategories['Subcategories'] as $trademeCategory) {
                $this->_createCategory($rootCatId, $trademeCategory, $active, $menu, $exclude);
            }

            return $this;
        }

        // if nothing called, just do the help
        echo $this->usageHelp();

        return $this;
    }

    /**
     * create a category
     *
     * @return void
     */
    protected function _createCategory($parentId, $trademeCategory, $active, $menu, $exclude)
    {
        if (in_array($trademeCategory['Number'], $exclude)) {
            return $this;
        }

        try {
            $parentCategory = Mage::getModel('catalog/category')->load($parentId);

            $category = Mage::getModel('catalog/category')
                ->setName(ucwords($trademeCategory['Name']))
                ->setData('trademe_category_name', $trademeCategory['Name'])
                ->setData('trademe_category_number', $trademeCategory['Number'])
                ->setData('trademe_category_path', $trademeCategory['Path'])
                ->setIsActive($active)
                ->setIncludeInMenu($menu)
                ->setPath($parentCategory->getPath())
                ->save();
            $catId = $category->getId();
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }

        foreach ($trademeCategory['Subcategories'] as $trademeSubcategory) {
            $this->_createCategory($catId, $trademeSubcategory, $active, $menu, $exclude);
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
Create Trade Me categories inside an existing category structure from the Trade Me public API:
https://developer.trademe.co.nz/api-reference/catalogue-methods/retrieve-general-categories/

WARNING: This doesn't de-duplicate. If the categories already exist it will make new ones anyway. URL keys may overlap.

Usage:  php -f trademe_category.php -- [options]

  --catid <id>             Target Magento category ID to create the structure under

  --tmjson <url>           Trade Me JSON url e.g. https://api.trademe.co.nz/v1/Categories.json
                           or https://api.trademe.co.nz/v1/Categories/0187-.json (for a subset)

  --active                 Set category to active? (default: false)

  --menu                   Set category to include in menu? (default: false)

  --exclude                Comma separated list of Trade Me category "Numbers" to exclude
                           (default: 0001-,0350-,5000-)

  help                     This help


USAGE;
    }
}

// run the shell script
$shell = new Etailer_Shell_TrademeCategory();
$shell->run();
