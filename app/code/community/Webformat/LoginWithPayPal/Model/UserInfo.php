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
class Webformat_LoginWithPayPal_Model_UserInfo extends Webformat_LoginWithPayPal_Model_AbstractRequest {
    /**
     * Retrieve access token from PayPal oauth.
     */
    public function retrieve() {
        $headers = array('Accept: application/json', 'Authorization: Bearer '
            . $this->getAccessToken()->getResponse()->access_token);
        $ch = curl_init($this->getServiceBaseUrl('/v1/identity/openidconnect/userinfo/?schema=openid'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (($output = curl_exec($ch)) === FALSE) {
            throw new Zend_Exception("Could not obtain user info");
        }
        curl_close($ch);
        $data = json_decode($output);
        $this->validateData($data);

        $rv = new Varien_Object();
        $rv->addData((array) $data);
        $this->setResponse($rv);

        return true;
    }

    /** Validate data. */
    public function validateData($data) {
        if (!$data) {
            throw new Zend_Exception("Could not obtain user info");
        }
        if (isset($data->error)) {
            throw new Zend_Exception($data->error_description);
        }
    }
}
