<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Consumers;

use FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bridge\BridgeInterface;

/**
 * Trait BridgeConsumer
 * @package FernleafSystems\Integrations\Paypal_Freeagent\Consumers
 */
trait BridgeConsumer {

	/**
	 * @var BridgeInterface
	 */
	private $oMiddleManShopBridge;

	/**
	 * @return BridgeInterface
	 */
	public function getBridge() {
		return $this->oMiddleManShopBridge;
	}

	/**
	 * @param BridgeInterface $oBridge
	 * @return $this
	 */
	public function setBridge( $oBridge ) {
		$this->oMiddleManShopBridge = $oBridge;
		return $this;
	}
}