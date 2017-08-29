<?php
namespace Dfe\PostFinance\W;
use Df\Framework\Controller\Result\Text;
/**
 * 2017-08-29
 */
final class Handler extends \Df\PaypalClone\W\Handler {
	/**
	 * 2017-08-29
	 * @override
	 * @see \Df\Payment\W\Handler::result()
	 * @used-by \Df\Payment\W\Handler::handle()
	 * @return Text
	 */
	protected function result() {return Text::i('');}
}