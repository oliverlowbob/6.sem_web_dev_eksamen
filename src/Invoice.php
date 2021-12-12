<?php
require_once("DatabaseConnector.php");

class Invoice extends DatabaseConnector
{

    function statusCode($status)
    {
        $statusInfo['status'] = $status;
        return $statusInfo;
    }

    public function addInvoice($customerId, $date, $total, $address="", $city="", $state="", $country="", $postalCode="")
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = 'INSERT INTO chinook_abridged.invoice (CustomerId, InvoiceDate, BillingAddress, BillingCity, BillingState, BillingCountry, BillingPostalCode, Total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = $con->prepare($sql);
            $response = $stmt->execute([htmlspecialchars($customerId), htmlspecialchars($date), htmlspecialchars($address), htmlspecialchars($city), htmlspecialchars($state), htmlspecialchars($country), htmlspecialchars($postalCode), htmlspecialchars($total)]);
            if($response == true){
                $invoiceId = $con->lastInsertId();
                $sql = 'SELECT * FROM chinook_abridged.Invoice WHERE InvoiceId=?';
                $stmt= $con->prepare($sql);
                $stmt->execute([$invoiceId]);

                while($row = $stmt->fetch()) {
                    $result['invoiceId'] = $row['InvoiceId'];
                    $users[] = $result;
                }

                return $users[0];
            }
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }
}
