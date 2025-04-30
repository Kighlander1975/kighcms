<?php
// /src/Controller/Backend/HierarchieController.php

namespace App\Controller\Backend;

use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/settings')]
final class HierarchieController extends AbstractController
{
    #[Route('/hierarchie', name: 'admin_settings_hierarchie')]
    public function index(SettingsRepository $settingsRepository, Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 10;

        $query = $settingsRepository->createQueryBuilder('s')
            ->where('s.name LIKE :prefix ESCAPE \'\\\'')
            ->orderBy('s.name', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->setParameter('prefix', 'hierarchy\_%')
            ->getQuery();

        $hierarchien = $query->getResult();

        $total = $settingsRepository->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->where('s.name LIKE :prefix ESCAPE \'\\\'')
            ->setParameter('prefix', 'hierarchy\_%')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('backend/hierarchie/index.html.twig', [
            'hierarchien' => $hierarchien,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ]);
    }
}