<?php
namespace Dfe\PostFinance;
/**
 * 2017-08-18
 * 2017-08-19
 * The charge parameters are described in the Chapter 4.2 «Form parameters» of the web documentation:
 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
 * @method Method m()
 * @method Settings s()
 */
final class Charge extends \Df\PaypalClone\Charge {
	/**
	 * 2017-08-19
	 * «Your affiliation name in our system»
	 * `[PostFinance] What is my PSPID?` https://mage2.pro/t/4349
	 * @override
	 * @see \Df\PaypalClone\Charge::k_MerchantId()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_MerchantId() {return 'PSPID';}

	/**
	 * 2017-08-18
	 * 2017-08-19
	 * «Your order number (merchant reference).
	 * The system checks that a payment has not been requested twice for the same order.
	 * The ORDERID has to be assigned dynamically.»
	 * @override
	 * @see \Df\PaypalClone\Charge::k_RequestId()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_RequestId() {return 'ORDERID';}

	/**
	 * 2017-08-18
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Signature()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Signature() {return '';}

	/**
	 * 2017-08-18
	 * @override
	 * @see \Df\PaypalClone\Charge::pCharge()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return array(string => mixed)
	 */
	protected function pCharge() {$s = $this->s(); return [];}
}