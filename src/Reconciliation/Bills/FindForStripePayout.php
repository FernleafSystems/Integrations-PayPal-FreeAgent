<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bills;

use FernleafSystems\ApiWrappers\Base\ConnectionConsumer;
use FernleafSystems\ApiWrappers\Freeagent\Entities;
use FernleafSystems\Integrations\Paypal_Freeagent\Consumers\PaypalTxnConsumer;

/**
 * Class FindForStripePayout
 * @package FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bills
 */
class FindForStripePayout {

	use ConnectionConsumer,
		PaypalTxnConsumer;

	/**
	 * @return bool
	 */
	public function hasBill() {
		return !empty( $this->find() );
	}

	/**
	 * @return Entities\Bills\BillVO|null
	 */
	public function find() {
		$oBill = null;
		$oPayout = $this->getPaypalTxn();

		if ( !empty( $oPayout->metadata[ 'ext_bill_id' ] ) ) {
			$oBill = ( new Entities\Bills\Retrieve() )
				->setConnection( $this->getConnection() )
				->setEntityId( $oPayout->metadata[ 'ext_bill_id' ] )
				->sendRequestWithVoResponse();
			if ( empty( $oBill ) ) {
				$oPayout->metadata[ 'ext_bill_id' ] = null;
				$oPayout->save();
			}
		}

		if ( empty( $oBill ) ) {
			try {
				$oBill = $this->findBillForStripePayout();
				$oPayout->metadata[ 'ext_bill_id' ] = $oBill->getId();
				$oPayout->save();
			}
			catch ( \Exception $oE ) {
				trigger_error( $oE->getMessage() );
			}
		}

		return $oBill;
	}

	/**
	 * @return Entities\Bills\BillVO|null
	 * @throws \Exception
	 */
	protected function findBillForStripePayout() {
		$oPayout = $this->getPaypalTxn();

		/** @var Entities\Bills\BillVO $oBill */
		$oBill = ( new Entities\Bills\Find() )
			->setConnection( $this->getConnection() )
			->filterByDateRange( $oPayout->arrival_date, 5 )
			->filterByReference( $oPayout->id )
			->first();

		if ( empty( $oBill ) ) {
			throw new \Exception( sprintf( 'Failed to find bill in FreeAgent for Payout transfer ID %s', $oPayout->id ) );
		}
		return $oBill;
	}
}