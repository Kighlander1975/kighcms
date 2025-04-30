<?php

namespace App\Service;

class ThemeService
{
    public function getActiveTheme(): string
    {
        // Aktuell hardcodiert, später z.B. aus Datenbank
        return 'default';
    }
}