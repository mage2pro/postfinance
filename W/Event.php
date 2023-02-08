<?php
namespace Dfe\PostFinance\W;
use Magento\Framework\Phrase;
/**
 * 2017-08-29
 * Note 1. «Integrate with PostFinance e-Commerce» → «7. Transaction feedback»
 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback
 * Note 2. «7.5 Feedback parameters»:
 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback#feedbackparameters
 * Note 3. Some examples of webhook notifications: https://mage2.pro/tags/postfinance-webhook
 */
final class Event extends \Df\PaypalClone\W\Event {
	/**
	 * 2017-09-01 `CN`: «Cardholder/customer name».
	 * @used-by \Dfe\PostFinance\Block\Info::prepare()
	 */
	function cardholder():string {return $this->r('CN');}

	/**
	 * 2017-09-01
	 * `BRAND`: «Card brand (our system derives this from the card number)».
	 * `CARDNO`: «Masked card number».
	 * @used-by \Dfe\PostFinance\Block\Info::prepare()
	 */
	function cardNumber():string {return dfp_card_format_last4(df_trim_left($this->r('CARDNO'), 'X'), $this->r('BRAND'));}

	/**
	 * 2017-09-01
	 * @used-by self::optionTitle()
	 * @used-by \Dfe\PostFinance\Block\Info::prepare()
	 */
	function isBankCard():bool {return 'CreditCard' === $this->option();}

	/**
	 * 2017-08-30
	 * `[PostFinance] Payment statuses`: https://mage2.pro/t/4343
	 * https://e-payment-postfinance.v-psp.com/en/en/guides/user%20guides/statuses-and-errors
	 * @override
	 * @see \Df\PaypalClone\W\Event::isSuccessful()
	 * @used-by \Df\Payment\W\Strategy\ConfirmPending::_handle()
	 */
	function isSuccessful():bool {return
		!in_array($this->s0(), [0, 1, 2]) && !in_array(intval($this->status()), [57, 59, 63, 73, 83, 93])
	;}

	/**
	 * 2017-09-01
	 * `BRAND`: «Card brand (our system derives this from the card number)».
	 * @used-by \Dfe\PostFinance\Choice::title()
	 * @return string|Phrase
	 */
	function optionTitle() {return $this->isBankCard()
		? __('Bank Card (%1)', $this->r('BRAND')) : dftr($this->option(), df_module_csv2($this, 'labels'))
	;}

	/**
	 * 2017-08-29 The type of the current transaction.
	 * @override
	 * @see \Df\PaypalClone\W\Event::ttCurrent()
	 * @used-by \Df\Payment\W\Strategy\ConfirmPending::_handle()
	 * @used-by \Df\PaypalClone\W\Nav::id()
	 */
	function ttCurrent():string {return !$this->isSuccessful() ? parent::ttCurrent() : dfa([
		4 => self::T_INFO
		,5 => self::T_AUTHORIZE
		,6 => self::T_VOID  # 2017-08-30 @todo
		,7 => self::T_INFO  # 2017-08-30 @todo
		,8 => self::T_REFUND # 2017-08-30 @todo
		,9 => self::T_CAPTURE
	], $this->s0());}

	/**
	 * 2017-08-29 «Payment reference in our system».
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_idE()
	 * @used-by \Df\PaypalClone\W\Event::idE()
	 */
	protected function k_idE():string {return 'PAYID';}

	/**
	 * 2017-08-29 «Your order reference».
	 * Despite the documentation spells it in the upper case (`ORDERID`),
	 * in my practice I always receive it as `orderID`: https://mage2.pro/tags/postfinance-webhook
	 * @override
	 * @see \Df\Payment\W\Event::k_pid()
	 * @used-by \Df\Payment\W\Event::pid()
	 */
	protected function k_pid():string {return 'orderID';}

	/**
	 * 2017-08-29 «SHA signature calculated by our system (if SHA-OUT configured)».
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_signature()
	 * @used-by \Df\PaypalClone\W\Event::signatureProvided()
	 */
	protected function k_signature():string {return self::K_SIGNATURE;}

	/**
	 * 2017-08-29 «Transaction status».
	 * `[PostFinance] Payment statuses`: https://mage2.pro/t/4343
	 * https://e-payment-postfinance.v-psp.com/en/en/guides/user%20guides/statuses-and-errors
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_status()
	 * @used-by \Df\PaypalClone\W\Event::status()
	 */
	protected function k_status():string {return 'STATUS';}

	/**
	 * 2017-09-01
	 * Note 1. «Payment method».
	 * Note 2. `The payment method codes (the possible values of the «PM» and «BRAND»
	 * webhook notification parameters)`: https://mage2.pro/t/4426
	 * @used-by self::isBankCard()
	 * @used-by self::optionTitle()
	 */
	private function option():int {return $this->r('PM');}

	/**
	 * 2017-08-30
	 * @used-by self::isSuccessful()
	 * @used-by self::ttCurrent()
	 */
	private function s0():int {return intval($this->status()[0]);}

	/**
	 * 2017-09-14
	 * @used-by self::k_signature()
	 * @used-by \Dfe\PostFinance\Signer::sign()
	 */
	const K_SIGNATURE = 'SHASIGN';
}