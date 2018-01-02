<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Consumers;

use FernleafSystems\Integrations\Paypal_Freeagent\DataWrapper\PaypalMerchantApi;

/**
 * Trait PaypalMerchantApiConsumer
 * @package FernleafSystems\Integrations\Paypal_Freeagent\Consumers
 */
trait PaypalMerchantApiConsumer {

	/**
	 * @var PaypalMerchantApi
	 */
	private $oPaypalMerchantApi;

	/**
	 * @return PaypalMerchantApi
	 */
	public function getPaypalMerchantApi() {
		return $this->oPaypalMerchantApi;
	}

	/**
	 * @param PaypalMerchantApi $oConfig
	 * @return $this
	 */
	public function setPaypalMerchantApi( $oConfig ) {
		$this->oPaypalMerchantApi = $oConfig;
		return $this;
	}
}