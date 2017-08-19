<?php
namespace Dfe\PostFinance;
use Magento\Sales\Model\Order\Address as OA;
/**
 * 2017-08-18
 * 2017-08-19
 * Note 1.
 * The charge parameters are described in the Chapter 4.2 «Form parameters» of the web documentation:
 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
 *
 * Note 2.
 * Although strictly taken the PSPID, ORDERID, AMOUNT, CURRENCY and LANGUAGE fields are sufficient,
 * we nevertheless strongly recommend you to also send us:
 * 		address (OWNERADDRESS)
 * 		country (OWNERCTY)
 * 		customer name (CN)
 * 		customer’s e-mail (EMAIL)
 * 		postcode/ZIP (OWNERZIP)
 * 		telephone number (OWNERTELNO)
 * 		town/city (OWNERTOWN)
 * as they can be useful tools for fraud prevention.
 *
 * Note 3.
 * The data format is described here:
 * https://e-payment-postfinance.v-psp.com/it/it/guides/integration%20guides/billpay/integration
 * It documents «BillPay», but I think the format is common for all PostFinance technologies.
 *
 * @method Method m()
 * @method Settings s()
 */
final class Charge extends \Df\PaypalClone\Charge {
	/**
	 * 2017-08-19
	 * «Amount to be paid,
	 * MULTIPLIED BY 100 since the format of the amount must not contain any decimals or other separators.
	 * The AMOUNT has to be assigned dynamically.»
	 * Required. «Numeric».
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Amount()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Amount() {return 'AMOUNT';}

	/**
	 * 2017-08-19
	 * «Currency of the order. ISO alpha code, e.g. EUR, USD, GBP, etc.».
	 * Required. «Alphanumeric, 3».
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Currency()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Currency() {return 'CURRENCY';}

	/**
	 * 2017-08-19 «Customer email address». Optional.
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Email()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Email() {return 'EMAIL';}
	
	/**
	 * 2017-08-19
	 * «Your affiliation name in our system».
	 * `[PostFinance] What is my PSPID?` https://mage2.pro/t/4349
	 * Required. «Alphanumeric, 30».
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
	 * Required. «Alphanumeric, 40».
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
	protected function pCharge() {
		$ba = $this->addressB(); /** @var OA $oa */
		$s = $this->s(); /** @var Settings $s */
		return [
			// 2017-08-19
			// «Customer name. Will be pre-initialised (but still editable)
			// in the Customer Name field of the credit card details.»
			// Optional.
			'CN' => $this->customerName()
			// 2017-08-19 «Customer street name and number». Optional.
			,'OWNERADDRESS' => df_cc_s($ba->getStreet())
			// 2017-08-19 «Customer country». Optional.
			,'OWNERCTY' => $ba->getCountryId()
			// 2017-08-19 «Customer telephone number». Optional.
			,'OWNERTELNO' => $this->addressBS()->getTelephone()
			// 2017-08-19 «Customer town/city/...». Optional.
			,'OWNERTOWN' => $ba->getCity()
			// 2017-08-19 «Customer postcode or ZIP code». Optional.
			,'OWNERZIP' => $ba->getPostcode()
		];
	}
}