<?php
namespace Dfe\PostFinance\Source\Hash;
# 2017-08-20 `[PostFinance] How to setup the «Hash algorithm» option?`: https://mage2.pro/t/4362
final class Algorithm extends \Df\Config\Source {
	/**
	 * 2017-08-20
	 * https://php.net/manual/function.hash.php
	 * hash('sha1', 'test')		https://3v4l.org/7nZfC
	 * hash('sha256', 'test')	https://3v4l.org/emM73
	 * hash('sha512', 'test')	https://3v4l.org/nErTM
	 * @override
	 * @see \Df\Config\Source::map()
	 * @used-by \Df\Config\Source::toOptionArray()
	 * @return array(string => string)
	 */
	protected function map():array {return df_map_r(function(int $v):string {return ["sha$v", "SHA-$v"];}, [1, 256, 512]);}
}