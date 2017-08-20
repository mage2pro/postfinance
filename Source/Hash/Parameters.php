<?php
namespace Dfe\PostFinance\Source\Hash;
// 2017-08-20
final class Parameters extends \Df\Config\Source {
	/**
	 * 2017-08-20
	 * @override
	 * @see \Df\Config\Source::map()
	 * @used-by \Df\Config\Source::toOptionArray()
	 * @return array(string => string)
	 */
	protected function map() {return [
		'main-only' => 'Main parameters only', self::ALL => 'Each parameter followed by the passphrase'
	];}

	/**
	 * 2017-08-20
	 * @used-by map()
	 * @var string
	 */
	const ALL = 'all';
}