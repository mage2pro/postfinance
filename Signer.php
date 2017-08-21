<?php
namespace Dfe\PostFinance;
/** 
 * 2017-08-20
 * SHA-IN:
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
 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce#shainsignature
 *
 * SHA-OUT:
 * *) All sent parameters (that appear in the SHA-OUT Parameter list), will be included in the string to hash.
 * *) All parameters need to be sorted alphabetically.
 * *) Parameters that do not have a value should NOT be included in the string to hash.
 * *) Even though some parameters are (partially) returned in lower case by our system,
 * for the SHA-OUT calculation each parameter must be put in upper case.
 * *) When you choose to transfer your test account to production via the link in the back-office menu,
 * a random SHA-OUT passphrase will be automatically configured in your production account.
 * For extra safety, we request that you use different SHA passphrases for TEST and PROD.
 * Please note that if they are found to be identical,
 * your TEST passphrase will be changed by our system (you will of course be notified).
 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback#redirectionwithdatabaseupdate_shaout
 *
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
		$s = $this->s(); /** @var Settings $s */
		$p = $this->v(); /** @var array(string => mixed) $p */
		// 2017-08-21 Whether the result should be SHA-IN or SHA-OUT.
		// 2017-08-21 `SHASIGN`: «SHA signature calculated by our system».
		// https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback#feedbackparameters
		/** @var string $password */
		$password = isset($p['SHASIGN']) ? $s->password2() : $s->password1();
		unset($p['SHASIGN']);
		/**
		 * 2017-08-21
		 * SHA-IN:
		 * «All parameter names should be in UPPERCASE (to avoid any case confusion)».
		 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/security-pre-payment-check#shainsignature_creatingthestring
		 * SHA-OUT:
		 * «Even though some parameters are (partially) returned in lower case by our system,
		 * for the SHA-OUT calculation each parameter must be put in upper case».
		 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback#redirectionwithdatabaseupdate_shaout
		 * The `marlon-ogone` library:
		 * 		$parameters = array_change_key_case($parameters, CASE_UPPER);
		 * https://github.com/marlon-be/marlon-ogone/blob/3.1.3/lib/Ogone/ParameterFilter/GeneralParameterFilter.php#L18
		 */
		$p = dfa_key_uc($p);
		/**
		 * 2017-08-21
		 * SHA-IN, SHA-OUT:
		 * «Parameters that do not have a value should NOT be included in the string to hash».
		 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/security-pre-payment-check#shainsignature_creatingthestring
		 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback#redirectionwithdatabaseupdate_shaout
		 * The `marlon-ogone` library:
		 *		array_walk($parameters, 'trim');
		 *		$parameters = array_filter($parameters, function ($value) {
		 *			return (bool) strlen($value);
		 *		});
		 * https://github.com/marlon-be/marlon-ogone/blob/3.1.3/lib/Ogone/ParameterFilter/GeneralParameterFilter.php#L19-L23
		 */
		$p = df_clean($p);
		/**
		 * 2017-08-21
		 * Note 1. SHA-IN. «All parameters have to be arranged alphabetically».
		 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/security-pre-payment-check#shainsignature_creatingthestring
		 * Note 2. SHA-OUT. «All parameters need to be sorted alphabetically».
		 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback#redirectionwithdatabaseupdate_shaout
		 * Note 3. The `marlon-ogone` library:
		 *		ksort($parameters);
		 * https://github.com/marlon-be/marlon-ogone/blob/3.1.3/lib/Ogone/ShaComposer/AllParametersShaComposer.php#L52
		 * Note 34.
		 * We can use @uses ksort() here, because the keys are already uppercased.
		 * @see \Dfe\AllPay\Signer::sign()
		 * https://github.com/mage2pro/allpay/blob/1.6.20/Signer.php#L18-L41
		 */
		ksort($p);
		/**
		 * 2017-08-21
		 * Note 1. SHA-IN.
		 * «The string that will be hashed is constructed by concatenating the values of the fields
		 * sent with the order, sorted alphabetically, in the format ‘PARAMETER=value’.
		 * Each parameter with its value is followed by a passphrase.».
		 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/security-pre-payment-check#shainsignature_creatingthestring
		 * Note 2. SHA-OUT.
		 * «The string to hash is constructed by concatenating the values of the fields sent with the order
		 * (sorted alphabetically, in the format ‘parameter=value’), followed by a passphrase.».
		 * https://e-payment-postfinance.v-psp.com/en/en/guides/integration%20guides/e-commerce/transaction-feedback#redirectionwithdatabaseupdate_shaout
		 * Note 3. The `marlon-ogone` library:
		 *		$shaString = '';
		 *		foreach ($parameters as $key => $value) {
		 *			$shaString .= $key . '=' . $value . $this->passphrase;
		 *		}
		 *		return strtoupper(hash($this->hashAlgorithm, $shaString));
		 * https://github.com/marlon-be/marlon-ogone/blob/3.1.3/lib/Ogone/ShaComposer/AllParametersShaComposer.php#L54-L60
		 * Note 4. The `marlon-ogone` library uses @see strtoupper(),
		 * but it is never mentioned in the documentation.
		 * Nevertheless, I have decided to use it too.
		 * @see hash() function's result is lowercased:
		 * https://github.com/mage2pro/postfinance/blob/0.0.8/Source/Hash/Algorithm.php#L9-L11
		 */
		return strtoupper(hash($s->hashAlgorithm(), df_map_k(function($k, $v) use($password){return
			"$k=$v$password"
		;}, $p)));
	}
}