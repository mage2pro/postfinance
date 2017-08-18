<?php
namespace Dfe\PostFinance\Signer;
// 2017-08-18
final class Response extends \Dfe\PostFinance\Signer {
	/**
	 * 2017-08-18
	 * @override
	 * @see \Dfe\PostFinance\Signer::values()
	 * @used-by \Dfe\PostFinance\Signer::sign()
	 * @return string[]
	 */
	protected function values() {return dfa_select_ordered($this->v(), []);}
}