<?php

namespace Komfort\App\Models;

class Vehicle extends BaseModel
{
    protected string $table = 'vehicles';

    public function create(array $data): int
    {
        if (!isset($data['uuid'])) {
            $data['uuid'] = $this->generateUuid();
        }
        
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }
        
        return parent::create($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = json_encode($data['features']);
        }
        
        return parent::update($id, $data);
    }

    public function getAvailable(): array
    {
        return $this->where('status', '=', 'available');
    }

    public function getByType(string $type): array
    {
        return $this->where('vehicle_type', '=', $type);
    }

    public function getByStatus(string $status): array
    {
        return $this->where('status', '=', $status);
    }

    public function updateStatus(int $vehicleId, string $status): bool
    {
        return $this->update($vehicleId, ['status' => $status]);
    }

    public function setAvailable(int $vehicleId): bool
    {
        return $this->updateStatus($vehicleId, 'available');
    }

    public function setInUse(int $vehicleId): bool
    {
        return $this->updateStatus($vehicleId, 'in_use');
    }

    public function setMaintenance(int $vehicleId): bool
    {
        return $this->updateStatus($vehicleId, 'maintenance');
    }

    public function getByCapacity(int $minCapacity): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE capacity >= :capacity AND status = 'available'"
        );
        $stmt->execute(['capacity' => $minCapacity]);
        return $stmt->fetchAll();
    }

    public function getWithPerformance(int $vehicleId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT v.*, 
                    (SELECT COUNT(*) FROM vehicle_assignments va WHERE va.vehicle_id = v.id) as total_assignments,
                    (SELECT SUM(total_revenue) FROM vehicle_performance vp WHERE vp.vehicle_id = v.id) as total_revenue
             FROM {$this->table} v
             WHERE v.id = :id"
        );
        $stmt->execute(['id' => $vehicleId]);
        $result = $stmt->fetch();
        
        if ($result && isset($result['features'])) {
            $result['features'] = json_decode($result['features'], true);
        }
        
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
}
