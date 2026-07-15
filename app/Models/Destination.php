<?php

namespace Komfort\App\Models;

class Destination extends BaseModel
{
    protected string $table = 'destinations';

    public function create(array $data): int
    {
        if (!isset($data['uuid'])) {
            $data['uuid'] = $this->generateUuid();
        }
        
        return parent::create($data);
    }

    public function getActive(): array
    {
        return $this->where('is_active', '=', true);
    }

    public function getByCountry(string $country): array
    {
        return $this->where('country', '=', $country);
    }

    public function getByRegion(string $region): array
    {
        return $this->where('region', '=', $region);
    }

    public function activate(int $destinationId): bool
    {
        return $this->update($destinationId, ['is_active' => true]);
    }

    public function deactivate(int $destinationId): bool
    {
        return $this->update($destinationId, ['is_active' => false]);
    }

    public function search(string $query): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
             WHERE name LIKE :query 
             OR description LIKE :query 
             OR location LIKE :query 
             OR country LIKE :query"
        );
        $searchTerm = "%{$query}%";
        $stmt->execute(['query' => $searchTerm]);
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
