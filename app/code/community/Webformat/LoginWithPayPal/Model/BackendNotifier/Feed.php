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
 * Observe for new feeds
 */
class Webformat_LoginWithPayPal_Model_BackendNotifier_Feed extends Mage_AdminNotification_Model_Feed {

    const CACHE_ITEM_ID = "webformat_loginwithpaypal_lastcheck";

    const XML_FEED_URL = "webformat_loginwithpaypal/feed/url";
    const XML_FEED_FREQUENCY = "webformat_loginwithpaypal/feed/frequency";

    /**
     * Retrieve feed url.
     * @return string
     */
    public function getFeedUrl() {
        return  (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH)
                ? 'https://' : 'http://')
                . Mage::getStoreConfig(self::XML_FEED_URL)
                . '&src='.urlencode(Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_UNSECURE_BASE_URL));
    }

    /**
     * Check feed for modification
     *
     * @return Webformat_LoginWithPayPal_Model_BackendNotifier_Feed
     */
    public function checkUpdate() {
        if (($this->getFrequency() + $this->getLastUpdate()) > time()) {
            return $this;
        }
        
        $feedData = array();
        $feedXml = $this->getFeedData();
        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                $feedData[] = array(
                    'severity'      => (int)$item->severity,
                    'date_added'    => $this->getDate((string)$item->pubDate),
                    'title'         => (string)$item->title,
                    'description'   => (string)$item->description,
                    'url'           => (string)$item->link,
                );
            }

            if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse(array_reverse($feedData));
            }
        }
        $this->setLastUpdate();
        
        return $this;
    }

    /**
     * Set last update time (now)
     * @return Webformat_LoginWithPayPal_Model_BackendNotifier_Feed
     */
    public function setLastUpdate() {
        Mage::app()->saveCache(time(), self::CACHE_ITEM_ID);
        return $this;
    }

    /**
     * Retrieve Last update time
     * @return int
     */
    public function getLastUpdate() {
        return Mage::app()->loadCache(self::CACHE_ITEM_ID);
    }

    /**
     * Retrieve Update Frequency
     *
     * @return int
     */
    public function getFrequency() {
        return Mage::getStoreConfig(self::XML_FEED_FREQUENCY) * 3600;
    }
}
