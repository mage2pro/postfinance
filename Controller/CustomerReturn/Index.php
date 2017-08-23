<?php
namespace Dfe\PostFinance\Controller\CustomerReturn;
use Df\Payment\Operation;
// 2017-08-23
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class Index extends \Df\Payment\CustomerReturn {
	/**
	 * 2017-08-23
	 * @override
	 * @see \Df\Payment\CustomerReturn::isSuccess()
	 * @used-by \Df\Payment\CustomerReturn::execute()
	 * @return string
	 */
	final protected function isSuccess() {return !df_request(Operation::FAILURE);}
}