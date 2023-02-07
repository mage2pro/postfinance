<?php
namespace Dfe\PostFinance\Init;
# 2017-08-18
/** @method \Dfe\PostFinance\Method m() */
final class Action extends \Df\PaypalClone\Init\Action {
	/**
	 * 2017-08-19
	 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formaction
	 * @override
	 * @see \Df\Payment\Init\Action::redirectUrl()
	 * @used-by \Df\Payment\Init\Action::action()
	 */
	protected function redirectUrl():string {return 'https://e-payment.postfinance.ch/ncol/{stage}/orderstandard_utf8.asp';}
}