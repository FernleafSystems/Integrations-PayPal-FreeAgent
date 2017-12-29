<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation;

use FernleafSystems\ApiWrappers\Base\ConnectionConsumer;
use FernleafSystems\ApiWrappers\Freeagent\Entities\BankTransactions\Retrieve;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\BankTransactionVoConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\BridgeConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\FreeagentConfigVoConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\PaypalTxnConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\StripePayoutConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bills\CreateForStripePayout;
use FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bills\ExplainBankTxnWithStripeBill;

/**
 * Class ProcessBillForStripePayout
 * @package FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation
 */
class ProcessBillForPayout {

	use BankTransactionVoConsumer,
		BridgeConsumer,
		ConnectionConsumer,
		FreeagentConfigVoConsumer,
		PaypalTxnConsumer;

	/**
	 * @throws \Exception
	 */
	public function run() {

		$this->refreshBankTxn(); // We do this to ensure we have the latest working BankTxn;

		$oBill = ( new CreateForStripePayout() )
			->setConnection( $this->getConnection() )
			->setStripePayout( $this->getStripePayout() )
			->setFreeagentConfigVO( $this->getFreeagentConfigVO() )
			->createBill();

		( new ExplainBankTxnWithStripeBill() )
			->setConnection( $this->getConnection() )
			->setStripePayout( $this->getStripePayout() )
			->setBankTransactionVo( $this->getBankTransactionVo() )
			->setFreeagentConfigVO( $this->getFreeagentConfigVO() )
			->process( $oBill );
	}

	protected function refreshBankTxn() {
		return $this->setBankTransactionVo(
			( new Retrieve() )
				->setConnection( $this->getConnection() )
				->setEntityId( $this->getBankTransactionVo()->getId() )
				->sendRequestWithVoResponse()
		);
	}
}