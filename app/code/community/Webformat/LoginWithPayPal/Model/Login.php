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

/** Login service. */
class Webformat_LoginWithPayPal_Model_Login extends Mage_Core_Model_Abstract {
    /**
     * Login with PayPal Code $code
     * @param string $code
     */
    public function login() {
        /* @var $grant Webformat_LoginWithPayPal_Model_Grant */
        $grant = Mage::getModel('webformat_loginwithpaypal/grant');
        $grant->setCode($this->getCode())->grant();

        /* @var $userInfo Webformat_LoginWithPayPal_Model_UserInfo */
        $userInfo = Mage::getModel('webformat_loginwithpaypal/userInfo');
        $userInfo->setAccessToken($grant)->retrieve();

        $this->setUserInfo($userInfo->getResponse());

        $this->validateUserInfo();
        $customer = $this->checkUserExistence();
        if ($customer !== false) {
            $this->loginWithExistentUser($customer);
        } else {
            $this->loginWithNewUser();
        }
        return true;
    }

    /**
     * Validate user info fields.
     */
    public function validateUserInfo() {
        if (!$this->getUserInfo()->hasEmail()) {
            throw new Zend_Exception("Can't retrieve user's email!");
        }
        if (!$this->getUserInfo()->hasGivenName()) {
            throw new Zend_Exception("Can't retrieve user's given name!");
        }
        if (!$this->getUserInfo()->hasFamilyName()) {
            throw new Zend_Exception("Can't retrieve user's family name!");
        }
    }

    /**
     * Check for user existence.
     * @return boolean
     */
    protected function checkUserExistence() {
        $customer = Mage::getModel("customer/customer")
                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($this->getUserInfo()->getEmail());
        return $customer->hasEntityId() ? $customer : false;
    }

    /**
     * Login with an existent user.
     * @param $customer Mage_Customer_Model_Customer
     */
    protected function loginWithExistentUser(Mage_Customer_Model_Customer $customer) {
        Mage::dispatchEvent('customer_customer_authenticated', array(
           'model'    => $customer,
           'password' => $customer->getPassword(),
        ));
        Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
        Mage::getSingleton('customer/session')->renewSession();

    }

    /**
     * Create a new user.
     * @return Mage_Customer_Model_Customer
     */
    protected function createNewUser() {
        $customer = Mage::getModel("customer/customer");
        $password = $customer->generatePassword();

        $userInfo = $this->getUserInfo();

        $customer->setFirstname($userInfo->getGivenName());
        $customer->setLastname($userInfo->getFamilyName());
        $customer->setEmail($userInfo->getEmail());
        $customer->setPassword($password);

        if ($userInfo->hasAddress()) {
            $address = new Varien_Object();
            $address->addData((array) $userInfo->getAddress());
            if ($userInfo->hasPhoneNumber()) {
                $address->setPhoneNumber($userInfo->getPhoneNumber());
            }
            $this->addAddress($customer, $address);
        }
        
        return $customer->save();
    }

    /**
     * Login with a new user.
     */
    protected function loginWithNewUser() {
        $this->loginWithExistentUser($this->createNewUser());
    }

    /**
     * Add address to customer
     * @param Mage_Customer_Model_Entity $customer
     * @param Varien_Object $addressData
     */
    public function addAddress($customer, $addressData) {
        $address = Mage::getModel('customer/address');
        $address->setIsDefaultBilling(true);
        $address->setIsDefaultShipping(true);
        $address->setFirstname($customer->getFirstname());
        $address->setLastname($customer->getLastname());
        $address->setTelephone($addressData->getPhoneNumber());
        $address->setPostcode($addressData->getPostalCode());
        $address->setCity($addressData->getLocality());
        $address->setStreet($addressData->getStreetAddress());
        $address->setCountryId($addressData->getCountry());

        $regionModel = Mage::getModel('directory/region')
            ->loadByCode($addressData->getRegion(),
                $addressData->getCountry());
        if ($regionModel->hasRegionId()) {
            $address->setRegionId($regionModel->getId());
        }
        $customer->addAddress($address);
    }
}