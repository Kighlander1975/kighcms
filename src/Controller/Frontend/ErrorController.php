<?php
// /src/Controller/Frontend/ErrorController.php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ThemeService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Twig\Environment;
use Throwable;

class ErrorController extends AbstractController
{
    private ThemeService $themeService;
    private string $themeBasePath = 'frontend/themes/';
    private Environment $twig;

    public function __construct(ThemeService $themeService, Environment $twig)
    {
        $this->themeService = $themeService;
        $this->twig = $twig;
    }

    public function show(Request $request, \Throwable $exception): Response
    {
        $theme = $this->themeService->getActiveTheme();
        $statusCode = $exception instanceof HttpException
            ? $exception->getStatusCode()
            : 500;

        $primaryPath   = $this->themeBasePath . "{$theme}/error/{$statusCode}.html.twig";
        $fallbackPath  = "@fallback_theme/error/{$statusCode}.html.twig";

        $params = [
            'status_code' => $statusCode,
            'exception' => $exception,
            'theme' => $theme,
            'base_template' => $this->themeBasePath . "{$theme}/base.html.twig",
        ];

        // 1. Theme
        if ($this->twig->getLoader()->exists($primaryPath)) {
            $template = $primaryPath;
        }
        // 2. Fallback
        elseif ($this->twig->getLoader()->exists($fallbackPath)) {
            $template = $fallbackPath;
            $params['base_template'] = "@fallback_theme/base.html.twig";
        }
        // 3. Notnagel
        else {
            throw new \LogicException('Kein Fehler-Template gefunden!');
        }

        return $this->render($template, $params, new Response('', $statusCode));
    }

}