<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/settings')]
final class SettingsController extends AbstractController
{
    #[Route('', name: 'admin_settings_index')]
    public function index(): Response
    {
        return $this->render('backend/settings/settings_all.html.twig', [
            'controller_name' => 'Backend/SettingsController',
        ]);
    }

    #[Route('/site', name: 'admin_settings_site')]
    public function site(): Response
    {
        return $this->render('backend/settings/settings_site.html.twig', []);
    }

    #[Route('/user', name: 'admin_settings_user')]
    public function user(): Response
    {
        return $this->render('backend/settings/settings_user.html.twig', []);
    }

    #[Route('/security', name: 'admin_settings_security')]
    public function security(): Response
    {
        return $this->render('backend/settings/settings_security.html.twig', []);
    }
}
