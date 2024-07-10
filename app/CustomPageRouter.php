<?php

namespace App;

use Illuminate\Support\Facades\Route;
use PHPageBuilder\Contracts\PageContract;
use PHPageBuilder\Contracts\PageTranslationContract;
use PHPageBuilder\Contracts\RouterContract;
use PHPageBuilder\Repositories\PageRepository;
use PHPageBuilder\Repositories\PageTranslationRepository;

class CustomPageRouter implements RouterContract
{
    protected $pageRepository;
    protected $pageTranslationRepository;
    protected $routeParameters = [];
    protected $routeToPageTranslationIdMapping = [];

    public function __construct()
    {
        $this->pageRepository = new PageRepository;
        $this->pageTranslationRepository = new PageTranslationRepository;
    }

    public function resolve($url)
    {
        // Strip URL query parameters
        $url = explode('?', $url, 2)[0];
        // Remove trailing slash
        $url = rtrim($url, '/');
        // Ensure we did not remove the root slash
        $url = empty($url) ? '/' : $url;

        // Verificar si la URL está definida en las rutas de Laravel
        $laravelRoute = Route::getRoutes()->match(request()->create($url));
        if ($laravelRoute->getName()) {
            return null; // La ruta está definida en Laravel, no hacer nada
        }

        // Continuar con la lógica actual para resolver la URL desde la base de datos
        $urlSegments = explode('/', $url);
        $pageTranslations = $this->pageTranslationRepository->getAll(['id', 'route']);
        $routes = [];
        foreach ($pageTranslations as $pageTranslation) {
            $route = $pageTranslation->route;
            $this->routeToPageTranslationIdMapping[$route] = $pageTranslation->id;
            $routeSegments = explode('/', $route);
            $routes[] = $routeSegments;
        }

        $orderedRoutes = $this->getRoutesInOrder($routes);

        foreach ($orderedRoutes as $routeSegments) {
            if ($this->onRoute($urlSegments, $routeSegments)) {
                $fullRoute = implode('/', $routeSegments);
                $matchedPage = $this->getMatchedPage($fullRoute, $this->routeToPageTranslationIdMapping[$fullRoute]);

                if ($matchedPage) {
                    global $phpb_route_parameters;
                    $phpb_route_parameters = $this->routeParameters;

                    return $matchedPage;
                }
            }
        }

        return null;
    }

    public function getRoutesInOrder($allRoutes)
    {
        usort($allRoutes, [$this, "routeOrderComparison"]);
        return $allRoutes;
    }

    public function routeOrderComparison($route1, $route2)
    {
        if (count($route1) > count($route2)) {
            return -1;
        }
        if (count($route1) < count($route2)) {
            return 1;
        }

        $namedParameterCountRoute1 = substr_count(implode('/', $route1), '{');
        $namedParameterCountRoute2 = substr_count(implode('/', $route2), '{');
        if ($namedParameterCountRoute1 < $namedParameterCountRoute2) {
            return -1;
        }
        if ($namedParameterCountRoute1 > $namedParameterCountRoute2) {
            return 1;
        }

        if (array_slice($route1, -1)[0] === '*') {
            return 1;
        }
        if (array_slice($route2, -1)[0] === '*') {
            return -1;
        }

        return 0;
    }

    public function getMatchedPage(string $matchedRoute, string $matchedPageTranslationId)
    {
        $pageTranslation = $this->pageTranslationRepository->findWithId($matchedPageTranslationId);
        if ($pageTranslation instanceof PageTranslationContract) {
            return $pageTranslation;
        }
        return null;
    }

    protected function onRoute($urlSegments, $routeSegments)
    {
        if (count($urlSegments) !== count($routeSegments) && end($routeSegments) !== '*') {
            return false;
        }

        $routeParameters = [];
        foreach ($routeSegments as $i => $routeSegment) {
            if (!isset($urlSegments[$i])) {
                return false;
            }
            $urlSegment = $urlSegments[$i];

            if (substr($routeSegment, 0, 1) === '{' && substr($routeSegment, -1) === '}') {
                $parameter = trim($routeSegment, '{}');
                $routeParameters[$parameter] = $urlSegment;
                continue;
            }
            if ($routeSegment === '*') {
                break;
            }
            if ($urlSegment === $routeSegment) {
                continue;
            }

            return false;
        }

        $this->routeParameters = $routeParameters;
        return true;
    }
}
