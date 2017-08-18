<?php
namespace Dfe\PostFinance\Init;
// 2017-08-18
/** @method \Dfe\PostFinance\Method m() */
final class Action extends \Df\PaypalClone\Init\Action {
	/**
	 * 2017-08-18
	 * @override
	 * @see \Df\Payment\Init\Action::redirectUrl()
	 * @used-by \Df\Payment\Init\Action::action()
	 * @return string
	 */
	protected function redirectUrl() {return '';}
}