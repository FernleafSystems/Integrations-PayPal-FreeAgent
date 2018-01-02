<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bridge;

use FernleafSystems\ApiWrappers\Freeagent\Entities;
use FernleafSystems\Integrations\Freeagent;

abstract class PaypalBridge implements Freeagent\Reconciliation\Bridge\BridgeInterface {

	/**
	 * This needs to be extended to add the Invoice Item details.
	 * @param string $sTxnID a Stripe Charge ID
	 * @return Freeagent\DataWrapper\ChargeVO
	 * @throws \Exception
	 */
	public function buildChargeFromTransaction( $sTxnID ) {
		$oCharge = new Freeagent\DataWrapper\ChargeVO();

		$oStripeCharge = Charge::retrieve( $sTxnID );
		$oBalTxn = BalanceTransaction::retrieve( $oStripeCharge->balance_transaction );

		return $oCharge->setId( $sTxnID )
					   ->setGateway( 'stripe' )
					   ->setPaymentTerms( 1 )
					   ->setAmount_Gross( $oBalTxn->amount/100 )
					   ->setAmount_Fee( $oBalTxn->fee/100 )
					   ->setAmount_Net( $oBalTxn->net/100 )
					   ->setDate( $oStripeCharge->created )
					   ->setCurrency( $oStripeCharge->currency );
	}

	/**
	 * @param string $sPayoutId
	 * @return Freeagent\DataWrapper\PayoutVO
	 */
	public function buildPayoutFromId( $sPayoutId ) {
		$oPayout = new Freeagent\DataWrapper\PayoutVO();
		$oPayout->setId( $sPayoutId );

		$oStripePayout = Payout::retrieve( $sPayoutId );
		try {
			foreach ( $this->getStripeBalanceTransactions( $oStripePayout ) as $oBalTxn ) {
				$oPayout->addCharge( $this->buildChargeFromTransaction( $oBalTxn->source ) );
			}
		}
		catch ( \Exception $oE ) {
		}

		$oPayout->setDateArrival( $oStripePayout->arrival_date )
				->setCurrency( $oStripePayout->currency );

		return $oPayout;
	}
}