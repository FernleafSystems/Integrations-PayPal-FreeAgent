<?php

namespace FernleafSystems\Integrations\Paypal_Freeagent\Reconciliation\Bridge;

use FernleafSystems\ApiWrappers\Freeagent\Entities\Contacts\ContactVO;
use FernleafSystems\ApiWrappers\Freeagent\Entities\Invoices\InvoiceVO;

interface BridgeInterface {

	/**
	 * @param string $sChargeTxnId
	 * @param bool   $bUpdateOnly
	 * @return ContactVO
	 */
	public function createFreeagentContact( $sChargeTxnId, $bUpdateOnly = false );

	/**
	 * @param string $sChargeTxnId
	 * @return InvoiceVO
	 */
	public function createFreeagentInvoiceFromTxn( $sChargeTxnId );

	/**
	 * @param string $sChargeTxnId
	 * @return int
	 */
	public function getFreeagentContactIdFromTxn( $sChargeTxnId );

	/**
	 * @param string $sChargeTxnId
	 * @return int
	 */
	public function getFreeagentInvoiceIdFromTxn( $sChargeTxnId );

	/**
	 * @param string $sChargeTxnId
	 * @return bool
	 */
	public function verifyStripeToInternalPaymentLink( $sChargeTxnId );
}