<?php
// src/Controller/Backend/DashboardController.php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('backend/dashboard/dashboard.html.twig', [
            'title' => 'Admin Dashboard'
        ]);
    }
}