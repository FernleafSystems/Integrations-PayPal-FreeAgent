<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\DataWrapper;

use FernleafSystems\Utilities\Data\Adapter\StdClassAdapter;

/**
 * Class PaypalMerchantApiConfig
 * @package FernleafSystems\Integrations\Paypal_Freeagent\DataWrapper
 */
class PaypalMerchantApiConfig {

	use StdClassAdapter;

	/**
	 * @return array
	 */
	public function getConfig() {
		return $this->getArrayParam( 'api_config' );
	}

	/**
	 * @param array $aApiConfig
	 * @return $this
	 */
	public function setConfig( $aApiConfig ) {
		return $this->setParam( 'api_config', $aApiConfig );
	}

}