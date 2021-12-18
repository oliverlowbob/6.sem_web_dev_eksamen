<?php
require_once("DatabaseConnector.php");

class Invoice extends DatabaseConnector
{

    function statusCode($status)
    {
        $statusInfo['status'] = $status;
        return $statusInfo;
    }

    public function addInvoice($customerId, $date, $cart, $address = "", $city = "", $state = "", $country = "", $postalCode = "")
    {
        $con = (new DatabaseConnector())->getConnection();
        if ($con) {
            try {
                $con->beginTransaction();

                $total = 0;

                foreach($cart as $track){
                    $getTotalSql = 'SELECT * FROM chinook_abridged.track WHERE TrackId=?';
                    $getStmt = $con->prepare($getTotalSql);
                    $getStmt->execute([$track['trackId']]);

                    while ($row = $getStmt->fetch()) {
                        $total += $row['UnitPrice'];
                    }

                }

                $sql = 'INSERT INTO chinook_abridged.invoice (CustomerId, InvoiceDate, BillingAddress, BillingCity, BillingState, BillingCountry, BillingPostalCode, Total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $stmt = $con->prepare($sql);
                $stmt->execute([htmlspecialchars($customerId), htmlspecialchars($date), htmlspecialchars($address), htmlspecialchars($city), htmlspecialchars($state), htmlspecialchars($country), htmlspecialchars($postalCode), htmlspecialchars($total)]);
                $invoiceId = $con->lastInsertId();

                foreach ($cart as $track) {
                    $invoiceLineSql = 'INSERT into invoiceline (InvoiceId, TrackId, UnitPrice, Quantity) 
                    VALUES (?, ?, ?, ?)';
                    $newStmt = $con->prepare($invoiceLineSql);
                    $newStmt->execute([$invoiceId, $track['trackId'], $track['unitPrice'], 1]);
                }

                $con->commit();
                $stmt = null;
                return "true";
            } catch (\Throwable $e) {
                $con->rollback();
                throw $e; // but the error must be handled anyway
            }
        } else {
            return $this->statusCode(ERROR);
        }
    }
}
