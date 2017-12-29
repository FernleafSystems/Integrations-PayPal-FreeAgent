<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation;

use FernleafSystems\ApiWrappers\Base\ConnectionConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\BankTransactionVoConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\BridgeConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\FreeagentConfigVoConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\PaypalTxnConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Invoices\ExplainBankTxnWithInvoices;
use FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Invoices\InvoicesVerify;

/**
 * Verifies all invoices associated with the payout are present and accurate within Freeagent
 * Then reconciles all local invoices/Stripe Charges with the exported invoices within Freeagent
 * Class StripeChargesWithFreeagentTransaction
 * @package iControlWP\Integration\FreeAgent\Reconciliation
 */
class ProcessInvoicesForPayout {

	use BankTransactionVoConsumer,
		BridgeConsumer,
		FreeagentConfigVoConsumer,
		ConnectionConsumer,
		PaypalTxnConsumer;

	/**
	 * @throws \Exception
	 */
	public function run() {

		$aReconInvoiceData = ( new InvoicesVerify() )
			->setConnection( $this->getConnection() )
			->setStripePayout( $this->getStripePayout() )
			->setBridge( $this->getBridge() )
			->run();

		( new ExplainBankTxnWithInvoices() )
			->setConnection( $this->getConnection() )
			->setStripePayout( $this->getStripePayout() )
			->setBridge( $this->getBridge() )
			->setBankTransactionVo( $this->getBankTransactionVo() )
			->run( $aReconInvoiceData );
	}
}