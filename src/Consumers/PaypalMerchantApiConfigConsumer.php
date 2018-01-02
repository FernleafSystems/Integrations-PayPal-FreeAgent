<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Consumers;

use FernleafSystems\Integrations\Paypal_Freeagent\DataWrapper\PaypalMerchantApiConfig;

/**
 * Trait PaypalMerchantApiConfigConsumer
 * @package FernleafSystems\Integrations\Paypal_Freeagent\Consumers
 */
trait PaypalMerchantApiConfigConsumer {

	/**
	 * @var PaypalMerchantApiConfig
	 */
	private $oPaypalMerchantApiConfig;

	/**
	 * @return PaypalMerchantApiConfig
	 */
	public function getPaypalMerchantApiConfig() {
		return $this->oPaypalMerchantApiConfig;
	}

	/**
	 * @param PaypalMerchantApiConfig $oConfig
	 * @return $this
	 */
	public function setPaypalMerchantApiConfig( $oConfig ) {
		$this->oPaypalMerchantApiConfig = $oConfig;
		return $this;
	}
}