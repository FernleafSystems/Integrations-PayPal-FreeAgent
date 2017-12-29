<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\BankTransactions;

use FernleafSystems\ApiWrappers\Base\ConnectionConsumer;
use FernleafSystems\ApiWrappers\Freeagent\Entities\BankTransactions\BankTransactionVO;
use FernleafSystems\ApiWrappers\Freeagent\Entities\BankTransactions\Create;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\BankAccountVoConsumer;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\PaypalTxnConsumer;

/**
 * Class CreateBankTransactionForStripePayout
 * @package FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\BankTransactions
 */
class CreateForPayout {

	use BankAccountVoConsumer,
		ConnectionConsumer,
		PaypalTxnConsumer;

	/**
	 * @return BankTransactionVO|null
	 * @throws \Exception
	 */
	public function create() {
		$oPayout = $this->getPaypalTxn();
		/** @var BankTransactionVO $oBankTxn */
		$bSuccess = ( new Create() )
			->setConnection( $this->getConnection() )
			->create(
				$this->getBankAccountVo(),
				$oPayout->arrival_date,
				$oPayout->amount/100,
				sprintf( 'Automatically create bank transaction for Stripe Payout %s', $oPayout->id )
			);

		$oBankTxn = null;
		if ( $bSuccess ) {
			sleep( 5 ); // to be extra sure it properly exists when we now try to find it.
			$oBankTxn = ( new FindForPayout() )
				->setConnection( $this->getConnection() )
				->setBankAccountVo( $this->getBankAccountVo() )
				->setPaypalTxn( $oPayout )
				->find();
		}
		return $oBankTxn;
	}
}