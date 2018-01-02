<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\DataWrapper;

use FernleafSystems\Utilities\Data\Adapter\StdClassAdapter;
use PayPal\Service\PayPalAPIInterfaceServiceService;

/**
 * Class PaypalMerchantApi
 * @package FernleafSystems\Integrations\Paypal_Freeagent\DataWrapper
 */
class PaypalMerchantApi {

	use StdClassAdapter;

	/**
	 * @return PayPalAPIInterfaceServiceService
	 */
	public function api() {
		return new PayPalAPIInterfaceServiceService( $this->getConfig() );
	}

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