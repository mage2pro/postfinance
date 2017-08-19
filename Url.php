<?php
namespace Dfe\PostFinance;
// 2017-08-19
final class Url extends \Df\Payment\Url {
	/**
	 * 2017-08-19
	 * Первый параметр — для test, второй — для live.
	 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#formaction
	 * @override
	 * @see \Df\Payment\Url::stageNames()
	 * @used-by \Df\Payment\Url::url()
	 * @return string[]
	 */
	protected function stageNames() {return ['test', 'prod'];}
}


