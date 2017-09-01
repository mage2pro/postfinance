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
	 * @used-by \Df\Payment\Block\Info::_prepareSpecificInformation()
	 */
	final protected function prepare() {
		$this->si('Payment Option', $this->choiceT());
	}
}