<?php
// src/app/code/community/Bikeexchange/Produktexport/Model/Api/Resource/Customer.php
/**
 * Class Bikeexchange_Produktexport_Model_Api_Resource_Customer
 */
class Bikeexchange_Produktexport_Model_Api_Resource_Customer
    extends Mage_Checkout_Model_Api_Resource_Customer
{
    /**
     * FIX guest order email blank.
     *
     * Mage using billing address email as customer email on guest orders.
     *
     * see http://magento.stackexchange.com/questions/90380/magento-customer-email-missing-in-guest-checkout-when-using-soap-api
     *
     * @param Mage_Sales_Model_Quote $quote
     *
     * @return $this
     */
    protected function _prepareGuestQuote(Mage_Sales_Model_Quote $quote)
    {
        $quote->setCustomerId(null)
            // ORIG: email always empty ->setCustomerEmail($quote->getBillingAddress()->getEmail())
            //->setCustomerEmail('mail@test.de')//$quote->getCustomerEmail()) // fix
            ->setCustomerIsGuest(true)
            ->setCustomerGroupId(Mage_Customer_Model_Group::NOT_LOGGED_IN_ID);
        return $this;
    }
}
