<?php
namespace Dfe\PostFinance\W;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/**
 * 2017-08-29
 */
final class Event extends \Df\PaypalClone\W\Event {
	/**
	 * 2017-08-29
	 * @override The type of the current transaction.
	 * @see \Df\PaypalClone\W\Event::ttCurrent()
	 * @used-by \Df\Payment\W\Strategy\ConfirmPending::_handle()
	 */
	function ttCurrent() {return '';}

	/**
	 * 2017-08-29
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_idE()
	 * @used-by \Df\PaypalClone\W\Event::idE()
	 * @return string
	 */
	protected function k_idE() {return '';}

	/**
	 * 2017-08-29
	 * @override
	 * @see \Df\Payment\W\Event::k_pid()
	 * @used-by \Df\Payment\W\Event::pid()
	 * @return string
	 */
	protected function k_pid() {return '';}

	/**
	 * 2017-08-29
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_signature()
	 * @used-by \Df\PaypalClone\W\Event::signatureProvided()
	 * @return string
	 */
	protected function k_signature() {return '';}

	/**
	 * 2017-08-29
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_status()
	 * @used-by \Df\PaypalClone\W\Event::status()
	 * @return string
	 */
	protected function k_status() {return '';}

	/**
	 * 2017-08-29
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_statusT()
	 * @used-by \Df\PaypalClone\W\Event::logTitleSuffix()
	 * @return string|null
	 */
	protected function k_statusT() {return '';}
}