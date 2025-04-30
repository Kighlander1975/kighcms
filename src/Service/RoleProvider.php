<?php
// /src/Service/RoleProvider.php

namespace App\Service;

use App\Repository\SettingsRepository;

class RoleProvider
{
    private SettingsRepository $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Gibt alle in den Settings verfügbaren Rollen zurück.
     */
    public function getAllSettingsRoles(): array
    {
        $roles = $this->settingsRepository->createQueryBuilder('s')
            ->where('s.name LIKE :prefix')
            ->setParameter('prefix', 'roles_%')
            ->getQuery()
            ->getResult();

        return array_map(fn($role) => $role->getValue(), $roles);
    }
}