<?php
/**
 * WEBFORMAT s.r.l.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Webformat
 * @package     LoginWithPayPal
 * @copyright   Copyright (c) 2014 WEBFORMAT s.r.l. (http://www.webformat.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<?php

/**
 * Block for form button login javascript.
 */
abstract class Webformat_LoginWithPayPal_Block_AbstractButton extends Mage_Core_Block_Template {
    /**
     * Constructor.
     */
    protected function _construct() {
        $this->setTemplate("webformat/loginwithpaypal/customer/form/login/button.phtml");
        Mage::getSingleton('customer/session')->setLoginFormName($this->getLoginFormName());
    }

    /**
     * Get PayPal Client Id App.
     * @return string
     */
    public function getClientId() {
        return Mage::helper('webformat_loginwithpaypal')->getClientId();
    }

    /**
     * Is sand box?
     * @return boolean
     */
    public function isSandBox() {
        return Mage::helper('webformat_loginwithpaypal')->isSandBox();
    }

    /**
     * Get return url.
     * @return String
     */
    public function getReturnUrl() {
        return Mage::getUrl('webformat_loginwithpaypal');
    }

    /**
     * Get current locale string.
     * @return String
     */
    public function getLocale() {
        return Mage::app()->getLocale()->getLocaleCode();
    }

    /**
     * Get scope for request.
     * @return string
     */
    public function getScope() {
        return Mage::getStoreConfig('webformat_loginwithpaypal/settings/scope');
    }

    /**
     * Get scope for request.
     * @return string
     */
    public function getButtonTheme() {
        return Mage::getStoreConfig('webformat_loginwithpaypal/theme/button');
    }

    /**
     * Is default theme?
     * @return boolean
     */
    public function isDefaultButtonTheme() {
        return $this->getButtonTheme() === 'blue';
    }

    /**
     * Get login form name for redirect page.
     */
    public abstract function getLoginFormName();
}
