<?php

namespace Komfort\App\Models;
use PDO;

class Booking extends BaseModel
{
    protected string $table = 'bookings';

    public function create(array $data): int
    {
        if (!isset($data['uuid'])) {
            $data['uuid'] = $this->generateUuid();
        }
        
        if (!isset($data['booking_reference'])) {
            $data['booking_reference'] = $this->generateBookingReference();
        }
        
        return parent::create($data);
    }

    public function getByUser(int $userId): array
    {
        return $this->where('user_id', '=', $userId);
    }

    public function getByStatus(string $status): array
    {
        return $this->where('status', '=', $status);
    }

    public function getByServiceType(int $serviceTypeId): array
    {
        return $this->where('service_type_id', '=', $serviceTypeId);
    }

    public function getByDestination(int $destinationId): array
    {
        return $this->where('destination_id', '=', $destinationId);
    }

    public function updateStatus(int $bookingId, string $status): bool
    {
        return $this->update($bookingId, ['status' => $status]);
    }

    public function confirm(int $bookingId): bool
    {
        return $this->updateStatus($bookingId, 'confirmed');
    }

    public function cancel(int $bookingId): bool
    {
        return $this->updateStatus($bookingId, 'cancelled');
    }

    public function complete(int $bookingId): bool
    {
        return $this->updateStatus($bookingId, 'completed');
    }

    public function getRecent(int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUpcoming(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE start_date >= CURDATE() 
             AND status IN ('pending', 'confirmed', 'paid')
             ORDER BY start_date ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getWithDetails(int $bookingId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, 
                    u.first_name, u.last_name, u.email,
                    st.name as service_type_name,
                    d.name as destination_name,
                    v.registration_number, v.make, v.model
             FROM {$this->table} b
             LEFT JOIN users u ON b.user_id = u.id
             LEFT JOIN service_types st ON b.service_type_id = st.id
             LEFT JOIN destinations d ON b.destination_id = d.id
             LEFT JOIN vehicles v ON b.vehicle_id = v.id
             WHERE b.id = :id"
        );
        $stmt->execute(['id' => $bookingId]);
        $result = $stmt->fetch();
        
        return $result ?: null;
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

    private function generateBookingReference(): string
    {
        return 'KFT' . strtoupper(uniqid()) . rand(100, 999);
    }
}
