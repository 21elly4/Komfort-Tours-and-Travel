<?php

namespace Komfort\App\Models;

class ServiceType extends BaseModel
{
    protected string $table = 'service_types';

    public function getActive(): array
    {
        return $this->where('is_active', '=', true);
    }

    public function findByName(string $name): ?array
    {
        $result = $this->where('name', '=', $name);
        return $result[0] ?? null;
    }

    public function activate(int $serviceTypeId): bool
    {
        return $this->update($serviceTypeId, ['is_active' => true]);
    }

    public function deactivate(int $serviceTypeId): bool
    {
        return $this->update($serviceTypeId, ['is_active' => false]);
    }
}
