<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bills;

use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\PaypalTxnConsumer;

/**
 * Class CountTotalFeesForStripePayout
 * @package FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bills
 */
class SumTotalFeesForStripePayout {

	use PaypalTxnConsumer;

	public function count() {

		$oFeeCollection = BalanceTransaction::all(
			array(
				'payout' => $this->getPaypalTxn()->id,
				'type'   => 'charge',
				'limit'  => 20
			)
		);

		$nTotalFees = 0;
		foreach ( $oFeeCollection->autoPagingIterator() as $oStripeFee ) {
			$nTotalFees += $oStripeFee->fee;
		}

		return $nTotalFees;
	}
}