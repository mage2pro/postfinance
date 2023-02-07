<?php
namespace Dfe\PostFinance\Block;
use Dfe\PostFinance\W\Event;
/**
 * 2017-08-18
 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
 * @method Event|string|null e(...$k)
 */
class Info extends \Df\Payment\Block\Info {
	/**
	 * 2017-08-18
	 * @override
	 * @see \Df\Payment\Block\Info::prepare()
	 * @used-by \Df\Payment\Block\Info::prepareToRendering()
	 */
	final protected function prepare():void {
		$e = $this->e(); /** @var Event $e */
		$this->siEx('PostFinance ID', df_tag('a', [
			'href' => 'https://mage2.pro/t/4430'
			,'target' => '_blank'
			,'title' => 'How to locate a transaction in the PostFinance merchant interface?'
		], $e->idE()));
		$this->si('Payment Option', $this->choiceT());
		if ($e->isBankCard()) {
			$this->si(['Card Number' => $e->cardNumber(), 'Cardholder' => $e->cardholder()]);
		}
	}
}