<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bridge;

use FernleafSystems\ApiWrappers\Freeagent\Entities;
use FernleafSystems\Integrations\Freeagent;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\PaypalMerchantApiConsumer;
use PayPal\PayPalAPI\GetTransactionDetailsReq;
use PayPal\PayPalAPI\GetTransactionDetailsRequestType;

abstract class PaypalBridge implements Freeagent\Reconciliation\Bridge\BridgeInterface {

	use PaypalMerchantApiConsumer,
		Freeagent\Consumers\FreeagentConfigVoConsumer;

	/**
	 * This needs to be extended to add the Invoice Item details.
	 * @param string $sTxnID a Stripe Charge ID
	 * @return Freeagent\DataWrapper\ChargeVO
	 * @throws \Exception
	 */
	public function buildChargeFromTransaction( $sTxnID ) {
		$oCharge = new Freeagent\DataWrapper\ChargeVO();

		try {
			$oDets = $this->getTxnChargeDetails( $sTxnID );

			$oCharge->gateway = 'paypalexpress';
			$oCharge->payment_terms = $this->getFreeagentConfigVO()->invoice_payment_terms;
			$oCharge->setId( $sTxnID )
					->setAmount_Gross( $oDets->GrossAmount->value )
					->setAmount_Fee( $oDets->FeeAmount->value )
					->setAmount_Net( $oDets->GrossAmount->value - $oDets->FeeAmount->value )
					->setDate( strtotime( $oDets->PaymentDate ) )
					->setCurrency( $oDets->GrossAmount->currencyID );
		}
		catch ( \Exception $oE ) {
		}

		return $oCharge;
	}

	/**
	 * This isn't applicable to PayPal
	 * @param string $sRefundId
	 * @return Freeagent\DataWrapper\RefundVO
	 */
	public function buildRefundFromId( $sRefundId ) {
		return null;
	}

	/**
	 * With Paypal, the Transaction and the Payout are essentially the same thing.
	 * @param string $sPayoutId
	 * @return Freeagent\DataWrapper\PayoutVO
	 */
	public function buildPayoutFromId( $sPayoutId ) {
		$oPayout = new Freeagent\DataWrapper\PayoutVO();
		$oPayout->setId( $sPayoutId );

		try {
			$oDets = $this->getTxnChargeDetails( $sPayoutId );
			$oPayout->date_arrival = strtotime( $oDets->PaymentDate );
			$oPayout->currency = $oDets->GrossAmount->currencyID;

			$oPayout->addCharge(
				$this->buildChargeFromTransaction( $sPayoutId )
			);
		}
		catch ( \Exception $oE ) {
		}

		return $oPayout;
	}

	/**
	 * @param string $sTxnID
	 * @return \PayPal\EBLBaseComponents\PaymentInfoType
	 * @throws \Exception
	 */
	protected function getTxnChargeDetails( $sTxnID ) {
		$oReqType = new GetTransactionDetailsRequestType();
		$oReqType->TransactionID = $sTxnID;

		$oReq = new GetTransactionDetailsReq();
		$oReq->GetTransactionDetailsRequest = $oReqType;

		return $this->getPaypalMerchantApi()
					->api()
					->GetTransactionDetails( $oReq )->PaymentTransactionDetails->PaymentInfo;
	}
}