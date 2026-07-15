<?php

namespace Komfort\App\Services;

use Komfort\Config\App;
use Komfort\Config\Logger;
use Komfort\Config\Database;

class MpesaService
{
    private string $env;
    private string $consumerKey;
    private string $consumerSecret;
    private string $passkey;
    private string $shortcode;
    private string $callbackUrl;

    public function __construct()
    {
        $this->env = App::get('mpesa.env', 'sandbox');
        $this->consumerKey = App::get('mpesa.consumer_key');
        $this->consumerSecret = App::get('mpesa.consumer_secret');
        $this->passkey = App::get('mpesa.passkey');
        $this->shortcode = App::get('mpesa.shortcode', '174379');
        $this->callbackUrl = App::get('app.url') . '/mpesa/callback';
    }

    /**
     * Generate OAuth access token
     */
    public function getAccessToken(): string
    {
        $url = $this->env === 'live' 
            ? 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $credentials = base64_encode($this->consumerKey . ':' . $this->consumerSecret);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['access_token'])) {
            return $result['access_token'];
        }

        Log::error('M-Pesa token generation failed', ['response' => $response]);
        throw new \Exception('Failed to generate M-Pesa access token');
    }

    /**
     * Initiate STK Push payment request
     */
    public function stkPush(string $phone, float $amount, string $bookingRef): array
    {
        try {
            $accessToken = $this->getAccessToken();
            $timestamp = date('YmdHis');
            $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

            $url = $this->env === 'live'
                ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
                : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

            $data = [
                'BusinessShortCode' => $this->shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $this->formatPhoneNumber($phone),
                'PartyB' => $this->shortcode,
                'PhoneNumber' => $this->formatPhoneNumber($phone),
                'CallBackURL' => $this->callbackUrl,
                'AccountReference' => $bookingRef,
                'TransactionDesc' => 'Payment for ' . $bookingRef
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = json_decode($response, true);

            Log::info('M-Pesa STK Push initiated', [
                'phone' => $phone,
                'amount' => $amount,
                'booking_ref' => $bookingRef,
                'response' => $result
            ]);

            return [
                'success' => $httpCode === 200,
                'response' => $result,
                'merchant_request_id' => $result['MerchantRequestID'] ?? null,
                'checkout_request_id' => $result['CheckoutRequestID'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push failed', [
                'error' => $e->getMessage(),
                'phone' => $phone,
                'amount' => $amount
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to M-Pesa format (254...)
     */
    private function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strpos($phone, '0') === 0) {
            $phone = '254' . substr($phone, 1);
        } elseif (strpos($phone, '+254') === 0) {
            $phone = substr($phone, 1);
        } elseif (strpos($phone, '254') !== 0) {
            $phone = '254' . $phone;
        }

        return $phone;
    }

    /**
     * Process payment callback
     */
    public function processCallback(array $callbackData): bool
    {
        try {
            $resultCode = $callbackData['Body']['stkCallback']['ResultCode'] ?? null;
            $merchantRequestId = $callbackData['Body']['stkCallback']['MerchantRequestID'] ?? null;
            $checkoutRequestId = $callbackData['Body']['stkCallback']['CheckoutRequestID'] ?? null;

            if ($resultCode === 0) {
                $callbackMetadata = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'] ?? [];
                $metadata = [];
                
                foreach ($callbackMetadata as $item) {
                    $metadata[$item['Name']] = $item['Value'] ?? null;
                }

                $mpesaReceipt = $metadata['MpesaReceiptNumber'] ?? null;
                $amount = $metadata['Amount'] ?? null;
                $phone = $metadata['PhoneNumber'] ?? null;
                $transactionDate = $metadata['TransactionDate'] ?? null;

                $this->updatePaymentStatus($merchantRequestId, $mpesaReceipt, $amount, $phone, $transactionDate);

                Log::info('M-Pesa payment successful', [
                    'merchant_request_id' => $merchantRequestId,
                    'mpesa_receipt' => $mpesaReceipt,
                    'amount' => $amount
                ]);

                return true;
            } else {
                $errorMessage = $callbackData['Body']['stkCallback']['ResultDesc'] ?? 'Payment failed';
                $this->markPaymentAsFailed($merchantRequestId, $errorMessage);

                Log::error('M-Pesa payment failed', [
                    'merchant_request_id' => $merchantRequestId,
                    'error' => $errorMessage
                ]);

                return false;
            }

        } catch (\Exception $e) {
            Log::error('M-Pesa callback processing failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Update payment status after successful callback
     */
    private function updatePaymentStatus(string $merchantRequestId, ?string $mpesaReceipt, ?float $amount, ?string $phone, ?string $transactionDate): void
    {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("
            UPDATE payments 
            SET status = 'paid',
                mpesa_receipt = ?,
                transaction_date = ?,
                metadata = JSON_SET(
                    COALESCE(metadata, '{}'),
                    '$.mpesa_receipt', ?,
                    '$.amount', ?,
                    '$.phone', ?,
                    '$.transaction_date', ?
                ),
                updated_at = NOW()
            WHERE merchant_request_id = ?
        ");
        
        $stmt->execute([
            $mpesaReceipt,
            $transactionDate,
            json_encode($mpesaReceipt),
            json_encode($amount),
            json_encode($phone),
            json_encode($transactionDate),
            $merchantRequestId
        ]);

        // Also update booking status
        $stmt = $db->prepare("
            UPDATE bookings 
            SET status = 'paid',
                updated_at = NOW()
            WHERE id = (SELECT booking_id FROM payments WHERE merchant_request_id = ?)
        ");
        
        $stmt->execute([$merchantRequestId]);
    }

    /**
     * Mark payment as failed
     */
    private function markPaymentAsFailed(string $merchantRequestId, string $errorMessage): void
    {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("
            UPDATE payments 
            SET status = 'failed',
                metadata = JSON_SET(
                    COALESCE(metadata, '{}'),
                    '$.error_message', ?
                ),
                updated_at = NOW()
            WHERE merchant_request_id = ?
        ");
        
        $stmt->execute([json_encode($errorMessage), $merchantRequestId]);
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus(string $checkoutRequestId): array
    {
        try {
            $accessToken = $this->getAccessToken();
            $timestamp = date('YmdHis');
            $password = base64_encode($this->shortcode . $this->passkey . $timestamp);

            $url = $this->env === 'live'
                ? 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query'
                : 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';

            $data = [
                'BusinessShortCode' => $this->shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            return [
                'success' => true,
                'response' => $result
            ];

        } catch (\Exception $e) {
            Log::error('M-Pesa status check failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
