<?php
// /src/Controller/Backend/SettingsController.php

namespace App\Controller\Backend;

use App\Form\SettingsVariableType;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/variables', name: 'admin_settings_variables')]
    public function variables(SettingsRepository $settingsRepository): Response
    {
        $settings = $settingsRepository->findAll();
        return $this->render('backend/settings/settings_variables.html.twig', [
            'settings' => $settings,
        ]);
    }

    #[Route('/new', name: 'admin_settings_variable_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SettingsVariableType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\Settings $data */
            $data = $form->getData();

            $em->persist($data);
            $em->flush();

            $this->addFlash('success', 'Die Variable wurde erfolgreich angelegt.');

            return $this->redirectToRoute('admin_settings_variables');
        }
        return $this->render('backend/settings/settings_variable_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_settings_variable_edit', requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em, SettingsRepository $settingsRepository): Response
    {
        $setting = $settingsRepository->find($id);
        if (!$setting) {
            throw $this->createNotFoundException('Variable nicht gefunden.');
        }

        // Setze das Präfix-Feld im Formular, sofern vorhanden
        $name = $setting->getName();
        $pre = null;
        if (str_contains($name, '_')) {
            [$pre, $rest] = explode('_', $name, 2);
            $pre = $pre . '_';
            // Für das Editing den Namen ohne Präfix für das Feld "name"
            $setting->setPre($pre);
            $setting->setName($rest);
        } else {
            $setting->setPre('');
        }

        $form = $this->createForm(SettingsVariableType::class, $setting);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Die Variable wurde erfolgreich bearbeitet.');
            return $this->redirectToRoute('admin_settings_variables');
        }

        return $this->render('backend/settings/settings_variable_edit.html.twig', [
            'form' => $form->createView(),
            'setting' => $setting,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_settings_variable_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, SettingsRepository $settingsRepository): Response
    {
        $setting = $settingsRepository->find($id);
        if (!$setting) {
            throw $this->createNotFoundException('Variable nicht gefunden.');
        }

        // Wenn das Formular bestätigt wurde und die Methode POST ist, löschen
        if ($request->isMethod('POST')) {
            if ($request->request->get('confirm') === 'yes') {
                $em->remove($setting);
                $em->flush();
                $this->addFlash('success', 'Die Variable wurde gelöscht.');
                return $this->redirectToRoute('admin_settings_variables');
            } else {
                // Abbruch, zurück zur Übersicht
                return $this->redirectToRoute('admin_settings_variables');
            }
        }

        return $this->render('backend/settings/settings_variable_delete.html.twig', [
            'setting' => $setting,
        ]);
    }
}
