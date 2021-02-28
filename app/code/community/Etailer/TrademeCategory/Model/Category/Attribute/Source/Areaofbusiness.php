<?php

class Etailer_TrademeCategory_Model_Category_Attribute_Source_Areaofbusiness extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = array(
                array(
                    'value' => Etailer_TrademeCategory_Model_Areaofbusiness::NOTSPECIFIED,
                    'label' => Mage::helper('core')->__('None Specified'),
                ),
                array(
                    'value' => Etailer_TrademeCategory_Model_Areaofbusiness::MARKETPLACE,
                    'label' => Mage::helper('core')->__('Marketplace'),
                ),
                array(
                    'value' => Etailer_TrademeCategory_Model_Areaofbusiness::PROPERTY,
                    'label' => Mage::helper('core')->__('Property'),
                ),
                array(
                    'value' => Etailer_TrademeCategory_Model_Areaofbusiness::MOTORS,
                    'label' => Mage::helper('core')->__('Motors'),
                ),
                array(
                    'value' => Etailer_TrademeCategory_Model_Areaofbusiness::JOBS,
                    'label' => Mage::helper('core')->__('Jobs'),
                ),
                array(
                    'value' => Etailer_TrademeCategory_Model_Areaofbusiness::SERVICES,
                    'label' => Mage::helper('core')->__('Services'),
                )
            );
        }
        return $this->_options;
    }
}
