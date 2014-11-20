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
class Webformat_LoginWithPayPal_Block_Customer_Form_Login_LoginWithPayPal extends Mage_Core_Block_Template {
    /**
     * Constructor.
     */
    protected function _construct() {
        $this->setTemplate("webformat/loginwithpaypal/customer/form/login/loginwithpaypal.phtml");
    }

    /**
     * Get redirect url.
     * @return url
     */
    public function getRedirectUrl() {
        if ($this->getError()) {
            return $this->getRedirectError();
        } else {
            return $this->getRedirectSuccess();
        }
    }
    
    /**
     * Redirect to error page.
     */
    public function getRedirectError() {
        return $this->getRedirectConfig('error');
    }

    /**
     * Redirect to success page.
     */
    public function getRedirectSuccess() {
        return $this->getRedirectConfig('success');
    }

    /**
     * 
     * @param string $code
     */
    public function getRedirectConfig($code) {
        return Mage::getUrl(Mage::getStoreConfig("webformat_loginwithpaypal/redirect/"
                . $code . "_" . $this->getLoginFormName()));
    }

    /**
     * Get Login form name (login or checkout page?)
     * @return string
     */
    public function getLoginFormName() {
        if (Mage::getSingleton('customer/session')->hasLoginFormName()) {
            return Mage::getSingleton('customer/session')->getLoginFormName();
        } else {
            return 'login';
        }
    }
}
