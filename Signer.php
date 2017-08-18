<?php
namespace Dfe\PostFinance;
/**
 * 2017-08-18
 * @see \Dfe\PostFinance\Signer\Request
 * @see \Dfe\PostFinance\Signer\Response
 * @method Settings s()
 */
abstract class Signer extends \Df\PaypalClone\Signer {
	/**
	 * 2017-08-18
	 * @used-by sign()
	 * @see \Dfe\PostFinance\Signer\Request::values()
	 * @see \Dfe\PostFinance\Signer\Response::values()
	 * @return string[]
	 */
	abstract protected function values();

	/**
	 * 2017-08-18
	 * @override
	 * @see \Df\PaypalClone\Signer::sign()
	 * @used-by \Df\PaypalClone\Signer::_sign()
	 * @return string
	 */
	final protected function sign() {return implode($this->values());}
}