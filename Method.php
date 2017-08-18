<?php
namespace Dfe\PostFinance;
// 2017-08-18
final class Method extends \Df\PaypalClone\Method {
	/**
	 * 2017-08-18
	 * @override
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by \Df\Payment\Method::isAvailable()
	 * @return null
	 */
	protected function amountLimits() {return null;}
}