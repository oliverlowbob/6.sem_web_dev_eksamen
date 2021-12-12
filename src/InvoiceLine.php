<?php
require_once("DatabaseConnector.php");

class InvoiceLine extends DatabaseConnector
{

    function statusCode($status)
    {
        $statusInfo['status'] = $status;
        return $statusInfo;
    }

    public function addInvoiceLine($invoiceId, $trackId, $unitPrice, $quantity)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = 'INSERT INTO chinook_abridged.invoiceline (InvoiceId, TrackId, UnitPrice, Quantity) VALUES (?, ?, ?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->execute([htmlspecialchars($invoiceId), htmlspecialchars($trackId), htmlspecialchars($unitPrice), htmlspecialchars($quantity)]);
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }
}
