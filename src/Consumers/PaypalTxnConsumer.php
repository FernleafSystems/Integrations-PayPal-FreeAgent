<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Consumers;

/**
 * Trait PaypalTxnConsumer
 * @package FernleafSystems\Integrations\Paypal_Freeagent\Consumers
 */
trait PaypalTxnConsumer {

	/**
	 * @var mixed
	 */
	private $oPaypalTxnConsumer;

	/**
	 * @return mixed
	 */
	public function getPaypalTxn() {
		return $this->oPaypalTxnConsumer;
	}

	/**
	 * @param $oVO
	 * @return $this
	 */
	public function setPaypalTxn( $oVO ) {
		$this->oPaypalTxnConsumer = $oVO;
		return $this;
	}
}