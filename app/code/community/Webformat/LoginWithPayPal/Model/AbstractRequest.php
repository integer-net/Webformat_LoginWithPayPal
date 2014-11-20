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

/** Access token. */
abstract class Webformat_LoginWithPayPal_Model_AbstractRequest extends Mage_Core_Model_Abstract {
    /**
     * Get service base url.
     * @return url
     */
    protected function getServiceBaseUrl($req = '') {
        $url = 'https://';
        if (Mage::helper('webformat_loginwithpaypal')->isSandbox()) {
            $url .= Mage::getStoreConfig('webformat_loginwithpaypal/settings/sandbox_endpoint');
        } else {
            $url .= Mage::getStoreConfig('webformat_loginwithpaypal/settings/endpoint');
        }
        return $url . $req;
    }
}