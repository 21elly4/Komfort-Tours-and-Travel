<?php

namespace Komfort\App\Models;

class Payment extends BaseModel
{
    protected string $table = 'payments';

    public function create(array $data): int
    {
        if (!isset($data['uuid'])) {
            $data['uuid'] = $this->generateUuid();
        }
        
        if (isset($data['metadata']) && is_array($data['metadata'])) {
            $data['metadata'] = json_encode($data['metadata']);
        }
        
        return parent::create($data);
    }

    public function getByBooking(int $bookingId): array
    {
        return $this->where('booking_id', '=', $bookingId);
    }

    public function getByStatus(string $status): array
    {
        return $this->where('status', '=', $status);
    }

    public function getByMethod(string $method): array
    {
        return $this->where('payment_method', '=', $method);
    }

    public function updateStatus(int $paymentId, string $status): bool
    {
        $data = ['status' => $status];
        
        if ($status === 'completed') {
            $data['payment_date'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($paymentId, $data);
    }

    public function complete(int $paymentId, string $transactionId = null): bool
    {
        $data = ['status' => 'completed', 'payment_date' => date('Y-m-d H:i:s')];
        
        if ($transactionId) {
            $data['transaction_id'] = $transactionId;
        }
        
        return $this->update($paymentId, $data);
    }

    public function fail(int $paymentId): bool
    {
        return $this->updateStatus($paymentId, 'failed');
    }

    public function refund(int $paymentId): bool
    {
        return $this->updateStatus($paymentId, 'refunded');
    }

    public function getTotalRevenue(): float
    {
        $stmt = $this->db->query(
            "SELECT SUM(amount) as total FROM {$this->table} WHERE status = 'completed'"
        );
        return (float)($stmt->fetch()['total'] ?? 0);
    }

    public function getRevenueByPeriod(string $startDate, string $endDate): array
    {
        $stmt = $this->db->prepare(
            "SELECT DATE(payment_date) as date, SUM(amount) as revenue, COUNT(*) as transactions
             FROM {$this->table} 
             WHERE status = 'completed' 
             AND payment_date BETWEEN :start_date AND :end_date
             GROUP BY DATE(payment_date)
             ORDER BY date ASC"
        );
        $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
        return $stmt->fetchAll();
    }

    private function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
