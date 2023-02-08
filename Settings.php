<?php
namespace Dfe\PostFinance;
# 2017-08-18
/** @method static Settings s() */
final class Settings extends \Df\Payment\Settings {
	/**
	 * 2017-08-21 «Hash algorithm».
	 * `[PostFinance] How to setup the «Hash algorithm» option?`: https://mage2.pro/t/4362
	 * @used-by \Dfe\PostFinance\Signer::sign()
	 */
	function hashAlgorithm():string {return $this->testable();}

	/**
	 * 2017-08-20 «SHA-IN pass phrase».
	 * `[PostFinance] How to setup my «SHA-IN pass phrase»?`: https://mage2.pro/t/4355
	 * @used-by \Dfe\PostFinance\Signer::sign()
	 */
	function password1():string {return $this->testableP();}

	/**
	 * 2017-08-20 «SHA-OUT pass phrase».
	 * `[PostFinance] How to setup my «SHA-OUT pass phrase»?`: https://mage2.pro/t/4359
	 * @used-by \Dfe\PostFinance\Signer::sign()
	 * @return string
	 */
	function password2() {return $this->testableP();}
}