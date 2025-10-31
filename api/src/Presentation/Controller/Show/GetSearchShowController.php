<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Show;

use App\Infrastructure\ApiPlatform\UseCase\Show\GetShowByNameUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[
    Route(
        path: '/shows/search',
        name: 'get_search_shows',
        methods: [Request::METHOD_GET],
    ),
]
class GetSearchShowController extends AbstractController
{
    public function __invoke(
        #[MapQueryParameter(name: 'searchTerms')] string $searchTerms,
        GetShowByNameUseCase $getShowByNameUseCase,
    ): Response {
        $searchTerms = str_replace('+', ' ', $searchTerms);
        $shows = $getShowByNameUseCase->execute($searchTerms);

        return new JsonResponse($shows);
    }
}
