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
 * 3.1) BillPay: https://e-payment-postfinance.v-psp.com/it/it/guides/integration%20guides/billpay/integration
 * 3.2) DirectLink (server-to-server)
 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/directlink#requestparameters
 * 3.3) [PostFinance] «Fraud Detection Module Advanced: Scoring», v.4.4.5: https://mage2.pro/t/4351
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
			/**
			 * 2017-08-19
			 * *) Integrate with PostFinance e-Commerce:
			 * «Customer name. Will be pre-initialised (but still editable)
			 * in the Customer Name field of the credit card details.»
			 * Optional.
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
			 * *) Fraud Detection Module:
			 * «The cardholder name can contain a maximum of 35 characters.
			 * This parameter can be sent via Ogone e-Commerce, DirectLink and Batch.
			 * Please note that for Ogone e-Commerce the cardholder’s name
			 * will also be captured via the Ogone payment page,
			 * where the cardholder’s name is a mandatory field.»
			 * Rules/Checks:
			 *	*) Name blacklist
			 *	*) Name greylist
			 *	*) Passenger name different from cardholder name.
			 * https://mage2.pro/t/4351
			 */
			'CN' => $this->customerName()
			/**
			 * 2017-08-19
			 * *) Integrate with PostFinance e-Commerce: «Customer street name and number». Optional.
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
			 * *) BillPay: «Invoicing address». Optional. «Alphanumeric, 35».
			 * https://e-payment-postfinance.v-psp.com/it/it/guides/integration%20guides/billpay/integration#deliveryinvoicingdata
			 * *) DirectLink (server-to-server): «Customer street name and number». Optional. «Alphanumeric, 50»
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/directlink#requestparameters
			 * *) Fraud Detection Module:
			 * «Customer’s address may contain a maximum of 35 characters.»
			 * Rules/Checks: «Invoicing address is a P.O. Box.»
			 * https://mage2.pro/t/4351
			 */
			,'OWNERADDRESS' => df_cc_s($ba->getStreet())
			// 2017-08-19 «Customer country». Optional. «Alphanumeric, 2».
			,'OWNERCTY' => $ba->getCountryId()
			// 2017-08-19 «Customer telephone number». Optional.
			,'OWNERTELNO' => $this->addressBS()->getTelephone()
			// 2017-08-19 «Customer town/city/...». Optional. «Alphanumeric, 25».
			,'OWNERTOWN' => $ba->getCity()
			// 2017-08-19 «Customer postcode or ZIP code». Optional. «Alphanumeric, 10».
			/**
			 * 2017-08-19
			 * *) Integrate with PostFinance e-Commerce:
			 * «Customer postcode or ZIP code», Optional.
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
			 * *) BillPay: «Invoicing zip/postcode». Optional. «Alphanumeric, 10».
			 * https://e-payment-postfinance.v-psp.com/it/it/guides/integration%20guides/billpay/integration#deliveryinvoicingdata
			 * *) DirectLink (server-to-server): «Customer’s postcode.». Optional. «Alphanumeric, 10»
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/directlink#requestparameters
			 * *) Fraud Detection Module:
			 * «Customer’s zip/postal code may contain a maximum of 10 characters»
			 * Rules/Checks:
			 *	*) Risky zip/postcodes
			 *	*) Advanced address verification check for specific card brands only
			 * https://mage2.pro/t/4351
			 */
			,'OWNERZIP' => $ba->getPostcode()
		];
	}
}