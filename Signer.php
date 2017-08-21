<?php
namespace Dfe\PostFinance;
/** 
 * 2017-08-20
 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#shainsignature
 * *) All parameters that you send
 * (and that appear in the list in List of parameters to be included in SHA-IN calculation)
 * will be included in the string-to-hash;
 * *) All parameter names should be in UPPERCASE (to avoid any case confusion);
 * *) All parameters have to be arranged alphabetically;
 * *) Parameters that do not have a value should NOT be included in the string to hash;
 * *) Some sorting algorithms place special characters in front of the first letter of the alphabet,
 * while others place them at the end. If in doubt, please respect the order as displayed in the SHA-list;
 * *) When you choose to transfer your test account to production via the link in the account menu,
 * a random SHA-IN passphrase will be automatically configured in your production account;
 * *) For extra safety, we request that you use different SHA passphrases in test and production.
 * If they are found to be identical, your TEST passphrase will be changed by our system
 * (you do get a notification mail for this).
 * 2017-08-21
 * «List of parameters to be included in SHA-IN calculation»:
 * https://e-payment-postfinance.v-psp.com/~/media/kdb/integration%20guides/sha-in_params.ashx?la=en
 * «SHA-OUT Parameter list»:
 * https://e-payment-postfinance.v-psp.com/~/media/kdb/integration%20guides/sha-out_params.ashx?la=en
 * @method Settings s()
 */
final class Signer extends \Df\PaypalClone\Signer {
	/**
	 * 2017-08-21
	 * @override
	 * @see \Df\PaypalClone\Signer::sign()
	 * @used-by \Df\PaypalClone\Signer::_sign()
	 * @return string
	 */
	final protected function sign() {
		return '';
	}
}