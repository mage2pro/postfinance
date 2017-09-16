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
 * 3.4) [PostFinance] «Fraud Detection Module Advanced: Checklist», v.4.4.5: https://mage2.pro/t/4352
 * @method Method m()
 * @method Settings s()
 */
final class Charge extends \Df\PaypalClone\Charge {
	/**
	 * 2017-08-19
	 * «Amount to be paid,
	 * MULTIPLIED BY 100 since the format of the amount must not contain any decimals or other separators.
	 * The AMOUNT has to be assigned dynamically.»
	 * Required. Numeric.
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Amount()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Amount() {return 'AMOUNT';}

	/**
	 * 2017-08-19
	 * «Currency of the order. ISO alpha code, e.g. EUR, USD, GBP, etc.».
	 * Required. Alphanumeric (3).
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
	 * Required. Alphanumeric (30).
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
	 * Required. Alphanumeric (40).
	 * 2017-08-21
	 * Despite the documentation says that an order number should be alphanumeric,
	 * really the numbers like «ORD-2017/08-01050» are allowed.
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
	protected function k_Signature() {return 'SHASIGN';}

	/**
	 * 2017-08-18
	 * @override
	 * @see \Df\PaypalClone\Charge::pCharge()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return array(string => mixed)
	 */
	protected function pCharge() {
		$ba = $this->addressB(); /** @var OA $oa */
		return [
			/**
			 * 2017-08-22
			 * «Integrate with PostFinance e-Commerce» → «7. Transaction feedback» →
			 * «7.2. Redirection depending on transaction result»:
			 *
			 * «There are four URLs which our system can redirect the customer to after a transaction,
			 * depending on the result.
			 * These are "ACCEPTURL", "EXCEPTIONURL", "CANCELURL" and "DECLINEURL".
			 * The URLs can be configured or submitted as follows:
			 * <...>
			 * Submission of the URLs in the hidden fields of the order form:
			 *	<input type="hidden" name="ACCEPTURL" value="">
			 *	<input type="hidden" name="DECLINEURL" value="">
			 *	<input type="hidden" name="EXCEPTIONURL" value="">
			 *	<input type="hidden" name="CANCELURL" value="">
			 *
			 * `ACCEPTURL`
			 * URL of the web page to display to the customer when the payment has been authorised (status 5),
			 * stored (status 4), accepted (status 9) or is waiting to be accepted (pending status 41, 51 or 91).
			 *
			 * `CANCELURL`
			 * URL of the web page to display to the customer when he cancels the payment (status 1).
			 * If this field is empty, the customer will be redirected to the DECLINEURL instead.
			 *
			 * `DECLINEURL`
			 * URL of the web page to show the customer when the acquirer declines the authorisation (status 2 or 93)
			 * more than the maximum permissible number of times.
			 *
			 * `EXCEPTIONURL`
			 * URL of the web page to display to the customer when the payment result is uncertain (status 52 or 92).
			 * If this field is empty, the customer will be redirected to the ACCEPTURL instead.»
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback#basicredirection
			 */
			'ACCEPTURL' => $this->customerReturnRemote()
			/**
			 * 2017-08-19
			 * *) Integrate with PostFinance e-Commerce:
			 * «Customer name. Will be pre-initialised (but still editable)
			 * in the Customer Name field of the credit card details.»
			 * Optional.
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
			 * *) BillPay: not mentioned.
			 * *) DirectLink (server-to-server): «Customer name». Optional. Alphanumeric (35).
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
			 * https://mage2.pro/t/4352
			 */
			,'CN' => $this->customerName()
			/**
			 * 2017-08-19
			 * *) Integrate with PostFinance e-Commerce: «Customer street name and number». Optional.
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
			 * *) BillPay: «Invoicing address». Optional. Alphanumeric (35).
			 * https://e-payment-postfinance.v-psp.com/it/it/guides/integration%20guides/billpay/integration#deliveryinvoicingdata
			 * *) DirectLink (server-to-server): «Customer street name and number». Optional. Alphanumeric (50).
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/directlink#requestparameters
			 * *) Fraud Detection Module:
			 * «Customer’s address may contain a maximum of 35 characters.»
			 * Rules/Checks: «Invoicing address is a P.O. Box.»
			 * https://mage2.pro/t/4351
			 * https://mage2.pro/t/4352
			 */
			,'OWNERADDRESS' => df_cc_s($ba->getStreet())
			/**
			 * 2017-08-19
			 * *) Integrate with PostFinance e-Commerce: «Customer country». Optional.
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
			 * *) BillPay: «Invoicing country code». Optional. Alphanumeric (2).
			 * https://e-payment-postfinance.v-psp.com/it/it/guides/integration%20guides/billpay/integration#deliveryinvoicingdata
			 * *) DirectLink (server-to-server): «Customer’s country, e.g. BE, NL, FR, etc.».
			 * Optional. Alphanumeric (2).
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/directlink#requestparameters
			 * *) Fraud Detection Module:
			 * «Customers invoicing country may contain a maximum of 2 characters.
			 * Country in ISO 3166-1-alpha-2 code as can be found on
			 * http://www.iso.org/iso/en/prodsservices/iso3166ma/02iso-3166-code-lists/list-en1.html.»
			 * Rules/Checks: «Number of different countries.»
			 * https://mage2.pro/t/4351
			 * https://mage2.pro/t/4352
			 * *) Fraud Detection Module (basic):
			 * «Before our system can apply the filters you set,
			 * you must either send your customer's country code
			 * in the hidden `OWNERCTY` field for each transaction,
			 * or enter `?` in the `OWNERCTY` field
			 * if you want our system to automatically detect your customer's country from his IP address.»
			 * https://mage2.pro/t/4353
			 */
			,'OWNERCTY' => $ba->getCountryId()
			/**
			 * 2017-08-19
			 * *) Integrate with PostFinance e-Commerce: «Customer telephone number». Optional.
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
			 * *) BillPay: not mentioned.
			 * *) DirectLink (server-to-server): «Customer’s telephone number». Optional. Alphanumeric (30).
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/directlink#requestparameters
			 * *) Fraud Detection Module:
			 * «Customer’s telephone number may contain a maximum of 30 characters for all Ogone modules
			 * with the exception of Ogone Batch which has a maximum of 20 characters.
			 * Special characters (“+” or “/” for instance) are allowed in this field.
			 * It’s best to be consistent in the way you send the phone numbers.»
			 * Rules/Checks:
			 * 	*) Telephone number greylist
			 * 	*) Telephone number blacklist
			 * https://mage2.pro/t/4351
			 * https://mage2.pro/t/4352
			 */
			,'OWNERTELNO' => $this->customerPhone()
			/**
			 * 2017-08-19
			 * *) Integrate with PostFinance e-Commerce: «Customer town/city/...». Optional.
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
			 * *) BillPay: «Invoicing city». Optional. Alphanumeric (25).
			 * *) DirectLink (server-to-server): «Customer’s town/city name». Optional. Alphanumeric (40).
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/directlink#requestparameters
			 * *) Fraud Detection Module: not mentioned.
			 */
			,'OWNERTOWN' => $ba->getCity()
			// 2017-08-19 «Customer postcode or ZIP code». Optional. Alphanumeric (10).
			/**
			 * 2017-08-19
			 * *) Integrate with PostFinance e-Commerce:
			 * «Customer postcode or ZIP code», Optional.
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formparameters
			 * *) BillPay: «Invoicing zip/postcode». Optional. Alphanumeric (10).
			 * https://e-payment-postfinance.v-psp.com/it/it/guides/integration%20guides/billpay/integration#deliveryinvoicingdata
			 * *) DirectLink (server-to-server): «Customer’s postcode.». Optional. Alphanumeric (10).
			 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/directlink#requestparameters
			 * *) Fraud Detection Module:
			 * «Customer’s zip/postal code may contain a maximum of 10 characters»
			 * Rules/Checks:
			 *	*) Risky zip/postcodes
			 *	*) Advanced address verification check for specific card brands only
			 * https://mage2.pro/t/4351
			 * https://mage2.pro/t/4352
			 */
			,'OWNERZIP' => $ba->getPostcode()
		/**
		 * 2017-08-22
		 * «Integrate with PostFinance e-Commerce» → «7. Transaction feedback» →
		 * «7.2. Redirection depending on transaction result»:
		 *
		 * «There are four URLs which our system can redirect the customer to after a transaction,
		 * depending on the result.
		 * These are "ACCEPTURL", "EXCEPTIONURL", "CANCELURL" and "DECLINEURL".
		 * The URLs can be configured or submitted as follows:
		 * <...>
		 * Submission of the URLs in the hidden fields of the order form:
		 *	<input type="hidden" name="ACCEPTURL" value="">
		 *	<input type="hidden" name="DECLINEURL" value="">
		 *	<input type="hidden" name="EXCEPTIONURL" value="">
		 *	<input type="hidden" name="CANCELURL" value="">
		 *
		 * `ACCEPTURL`
		 * URL of the web page to display to the customer when the payment has been authorised (status 5),
		 * stored (status 4), accepted (status 9) or is waiting to be accepted (pending status 41, 51 or 91).
		 *
		 * `CANCELURL`
		 * URL of the web page to display to the customer when he cancels the payment (status 1).
		 * If this field is empty, the customer will be redirected to the DECLINEURL instead.
		 *
		 * `DECLINEURL`
		 * URL of the web page to show the customer when the acquirer declines the authorisation (status 2 or 93)
		 * more than the maximum permissible number of times.
		 *
		 * `EXCEPTIONURL`
		 * URL of the web page to display to the customer when the payment result is uncertain (status 52 or 92).
		 * If this field is empty, the customer will be redirected to the ACCEPTURL instead.»
		 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback#basicredirection
		 */
		] + array_fill_keys(['CANCELURL', 'DECLINEURL', 'EXCEPTIONURL'],
			$this->customerReturnRemoteWithFailure()
		);
	}
}