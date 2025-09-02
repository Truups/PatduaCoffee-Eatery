<?php

include($_SERVER['DOCUMENT_ROOT'] . '/service/database.php');

// Callback token xendit
$callback_token = 'AAl6JYdxiVu54hQ88vufd0D0LOHTKkKRY2iV9FUjIzQDfXCt';

//Verifikasi Webhook
$headers = getallheaders();
$webhook_id = $headers['webhook-id'] ?? $headers['Webhook-Id'] ?? '';
$timestamp = $headers['webhook-timestamp'] ?? $headers['Webhook-Timestamp'] ?? '';
$signature = $headers['webhook-signature'] ?? $headers['Webhook-Signature'] ?? '';

//Raw body
$raw_body = file_get_contents('php://input');
file_put_contents("log_rawbody.txt", $raw_body);

//Verify signature function
function verifyWebhookS($raw_body, $timestamp, $signature, $callback_token)
{
    if (empty($timestamp) || empty($signature)) {
        return false;
    }
    
    $signed_payload = $timestamp . $raw_body;
    $expected_signature = hash_hmac('sha256', $signed_payload, $callback_token);
    return hash_equals('v1=' . $expected_signature, $signature);
}

// Verifikasi signature (opsional untuk development, wajib untuk production)
if (!empty($signature)) 
{
    if (!verifyWebhookS($raw_body, $timestamp, $signature, $callback_token)) 
    {
        http_response_code(401);
        exit('Invalid signature');
    }
}

//Parse JSON
$data = json_decode($raw_body, true);


if (json_last_error() !== JSON_ERROR_NONE) 
{
    error_log("Invalid JSON: " . json_last_error_msg());
    http_response_code(200); // Tetap reply OK agar Xendit tidak error 400
    echo json_encode(['status' => 'ignored']);
    exit;
}

if (!isset($data['event'])) 
{
    error_log("Missing 'event' field");
    http_response_code(200);
    echo json_encode(['status' => 'ignored']);
    exit;
}


//Log untuk debugging
error_log("Xendit Callback: " . $raw_body);

//Event Handler
$event = strtolower($data['event']);
$payload = $data['data'] ?? '';

switch ($event) 
{
    case 'invoice.paid':
        handleInvoicePaid($payload, $db);
        break;
    case 'invoice.expired':
        handleInvoiceExpired($payload, $db);
        break;
    case 'invoice.failed':
        handleInvoiceFailed($payload, $db);
        break;
    default:
        error_log("Unknown event: " . $event);
        break;
}

//Define Functions
function handleInvoicePaid($data, $db)
{
    $external_id = $data['external_id'] ?? '';
    $invoice_id = $data['id'] ?? '';
    $amount = $data['amount'] ?? 0;
    $paid_amount = $data['paid_amount'] ?? 0;

    if (empty($external_id) || empty($invoice_id)) 
    {
        error_log("Missing required fields in invoice.paid event");
        return;
    }

    //Update status pembayaran database
    $stmt = $db->prepare("UPDATE transaksi SET
                            status_pembayaran = 'paid',
                            tanggal_bayar = NOW(),
                            jumlah_dibayar = ?
                            WHERE kode_Order = ? AND xendit_invoice_id = ?");

    $stmt->bind_param("dss", $paid_amount, $external_id, $invoice_id);
    
    if ($stmt->execute()) 
    {
        error_log("Payment successful for order: " . $external_id);
    } else 
    {
        error_log("Failed to update payment status for order: " . $external_id);
    }
}

function handleInvoiceExpired($data, $db) 
{
    $external_id = $data['external_id'] ?? '';
    $invoice_id = $data['id'] ?? '';
    
    if (empty($external_id) || empty($invoice_id)) 
    {
        error_log("Missing required fields in invoice.expired event");
        return;
    }
    
    $stmt = $db->prepare("UPDATE transaksi SET status_pembayaran = 'expired' WHERE kode_Order = ? AND xendit_invoice_id = ?");
    $stmt->bind_param("ss", $external_id, $invoice_id);
    
    if ($stmt->execute()) 
    {
        error_log("Payment expired for order: " . $external_id);
    } else 
    {
        error_log("Failed to update expired status for order: " . $external_id);
    }
}

function handleInvoiceFailed($data, $db) 
{
    $external_id = $data['external_id'] ?? '';
    $invoice_id = $data['id'] ?? '';
    
    if (empty($external_id) || empty($invoice_id)) 
    {
        error_log("Missing required fields in invoice.failed event");
        return;
    }
    
    $stmt = $db->prepare("UPDATE transaksi SET status_pembayaran = 'failed' WHERE kode_Order = ? AND xendit_invoice_id = ?");
    $stmt->bind_param("ss", $external_id, $invoice_id);
    
    if ($stmt->execute()) 
    {
        error_log("Payment failed for order: " . $external_id);
    } else 
    {
        error_log("Failed to update failed status for order: " . $external_id);
    }
}

http_response_code(200);
echo json_encode(['status' => 'success']);
?>