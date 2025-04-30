<?php
namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Service\ThemeService;

class FrontendController extends AbstractController
{
    private ThemeService $themeService;
    private string $themeBasePath = 'frontend/themes/';

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }


    // Startseite unter / und /home erreichbar
    #[Route('/', name: 'homepage')]
    #[Route('/home', name: 'homepage_alias')]
    public function homepage(): Response
    {
        return $this->renderPage('home');
    }

    // Catch-All-Routing, auch mit Hierarchie (z.B. /events/party_jens)
    #[Route(
        '/{slug}',
        name: 'page',
        requirements: ['slug' => '^(?!login$|logout$|register$).+']
    )]
    public function page(string $slug): Response
    {
        return $this->renderPage($slug);
    }

    private function renderPage(string $slug): Response
    {
        $theme = $this->themeService->getActiveTheme();
        $baseTemplate = "{$this->themeBasePath}{$theme}/base.html.twig";

        // Dummy/Platzhalter für spätere Datenbankabfrage
        $pageData = $this->getPageDataBySlug($slug);

        if (!$pageData) {
            throw new NotFoundHttpException('Seite nicht gefunden.');
        }

        // Template-Name dynamisch nach hinterlegtem Layout
        $layout = $pageData['layout'] ?? 'home';
        $layoutTemplate = "{$this->themeBasePath}{$theme}/pages/{$layout}.html.twig";

        if (!$this->getTwig()->getLoader()->exists($layoutTemplate)) {
            throw new NotFoundHttpException('Layout-Template nicht gefunden: ' . $layoutTemplate);
        }

        return $this->render($layoutTemplate, [
            'theme'   => $theme,
            'slug'    => $slug,
            'title'   => $pageData['title'],
            'content' => $pageData['content'],
            'base_template'=> $baseTemplate, // <- NEU
        ]);
    }

    private function getPageDataBySlug(string $slug): ?array
    {
        // Dummy-Daten wie in der Datenbank später
        $pages = [
            'home' => [
                'title'   => 'Startseite',
                'layout'  => 'home',
                'content' => 'Das ist die Startseite.',
            ],
            'imprint' => [
                'title'   => 'Impressum',
                'layout'  => 'home',
                'content' => '<h1>Impressum</h1><p>Hier steht dein Impressumstext.</p>',
            ],
            'events/party_jens' => [
                'title'   => 'Party bei Jens',
                'layout'  => 'home',
                'content' => '<h1>Einladung!</h1><p>Party am Samstag!</p>',
            ],
        ];
        return $pages[$slug] ?? null;
    }

    private function getTwig(): \Twig\Environment
    {
        return $this->container->get('twig');
    }
}