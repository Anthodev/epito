<?php

declare(strict_types=1);

namespace App\Tests\Api\Presentation\Controller\Show;

use App\Domain\Model\ApiShow\ApiShow;
use App\Infrastructure\ApiPlatform\UseCase\Show\GetShowByNameUseCase;
use App\Infrastructure\Exception\ShowsNotFoundException;
use App\Presentation\Controller\Show\GetSearchShowController;
use App\Shared\Enum\ShowStatusEnum;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Unit tests for GetSearchShowController
 *
 * Tests the controller logic that handles /shows/search requests,
 * including parameter processing and integration with the use case.
 */
class GetSearchShowControllerTest extends TestCase
{
    private GetSearchShowController $controller;

    private GetShowByNameUseCase&MockObject $useCaseMock;

    protected function setUp(): void
    {
        $this->useCaseMock = $this->createMock(GetShowByNameUseCase::class);
        $this->controller = new GetSearchShowController();
    }

    /**
     * Test successful search with single show result
     */
    public function testInvokeWithSingleShowResult(): void
    {
        $searchTerms = "Breaking Bad";

        $show = new ApiShow(
            name: "Breaking Bad",
            summary: "A high school chemistry teacher turned methamphetamine manufacturer",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::ENDED,
            id: 169,
            averageRuntime: 47,
            premiered: new \DateTimeImmutable("2008-01-20"),
            ended: new \DateTimeImmutable("2013-09-29"),
            image: "breaking-bad.jpg",
            genres: ["Crime", "Drama", "Thriller"],
            theTvDbId: 81189,
            imdbId: "tt0903747",
            episodes: [],
        );

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($searchTerms)
            ->willReturn([$show]);

        $response = $this->controller->__invoke(
            $searchTerms,
            $this->useCaseMock,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertCount(1, $responseData);
        $this->assertArrayHasKey("name", $responseData[0]);
        $this->assertSame("Breaking Bad", $responseData[0]["name"]);
    }

    /**
     * Test search with multiple show results
     */
    public function testInvokeWithMultipleShowResults(): void
    {
        $searchTerms = "The Flash";

        $show1 = new ApiShow(
            name: "The Flash",
            summary: "The original Flash series",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::ENDED,
            id: 13,
            averageRuntime: 44,
            premiered: new \DateTimeImmutable("1990-09-20"),
            ended: new \DateTimeImmutable("1991-05-18"),
            image: null,
            genres: ["Drama", "Science-Fiction"],
            theTvDbId: 77765,
            imdbId: "tt0098798",
            episodes: [],
        );

        $show2 = new ApiShow(
            name: "The Flash",
            summary: "The modern Flash series",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::ENDED,
            id: 279,
            averageRuntime: 44,
            premiered: new \DateTimeImmutable("2014-10-07"),
            ended: new \DateTimeImmutable("2023-05-24"),
            image: null,
            genres: ["Drama", "Science-Fiction"],
            theTvDbId: 279121,
            imdbId: "tt3107288",
            episodes: [],
        );

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($searchTerms)
            ->willReturn([$show1, $show2]);

        $response = $this->controller->__invoke(
            $searchTerms,
            $this->useCaseMock,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(2, $responseData);
        $this->assertSame("The Flash", $responseData[0]["name"]);
        $this->assertSame("The Flash", $responseData[1]["name"]);
    }

    /**
     * Test search with plus signs in URL-encoded parameters
     *
     * The controller should replace '+' with spaces before calling the use case.
     */
    public function testInvokeWithPlusSignsConvertedToSpaces(): void
    {
        $expectedSearchTerms = "It's Always Sunny in Philadelphia";
        $receivedSearchTerms = "It's+Always+Sunny+in+Philadelphia";

        $show = new ApiShow(
            name: "It's Always Sunny in Philadelphia",
            summary: "Four friends run a bar together",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::RUNNING,
            id: 299,
            averageRuntime: 22,
            premiered: new \DateTimeImmutable("2005-08-04"),
            ended: null,
            image: "sunny.jpg",
            genres: ["Comedy"],
            theTvDbId: 76703,
            imdbId: "tt0472954",
            episodes: [],
        );

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($expectedSearchTerms)
            ->willReturn([$show]);

        $response = $this->controller->__invoke(
            $receivedSearchTerms,
            $this->useCaseMock,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertSame(
            "It's Always Sunny in Philadelphia",
            $responseData[0]["name"],
        );
    }

    /**
     * Test search returns empty array when no results found
     */
    public function testInvokeReturnsEmptyArrayWhenNoResultsFound(): void
    {
        $searchTerms = "NonExistentShow12345";

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($searchTerms)
            ->willReturn([]);

        $response = $this->controller->__invoke(
            $searchTerms,
            $this->useCaseMock,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertEmpty($responseData);
    }

    /**
     * Test search response contains required show fields
     */
    public function testInvokeResponseContainsRequiredShowFields(): void
    {
        $searchTerms = "Breaking Bad";

        $show = new ApiShow(
            name: "Breaking Bad",
            summary: "A chemistry teacher turned meth manufacturer",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::ENDED,
            id: 169,
            averageRuntime: 47,
            premiered: new \DateTimeImmutable("2008-01-20"),
            ended: new \DateTimeImmutable("2013-09-29"),
            image: "breaking-bad.jpg",
            genres: ["Crime", "Drama", "Thriller"],
            theTvDbId: 81189,
            imdbId: "tt0903747",
            episodes: [],
        );

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($searchTerms)
            ->willReturn([$show]);

        $response = $this->controller->__invoke(
            $searchTerms,
            $this->useCaseMock,
        );

        $responseData = json_decode($response->getContent(), true);

        $showData = $responseData[0];
        $this->assertArrayHasKey("name", $showData);
        $this->assertArrayHasKey("type", $showData);
        $this->assertArrayHasKey("language", $showData);
        $this->assertArrayHasKey("genres", $showData);
        $this->assertArrayHasKey("status", $showData);
        $this->assertArrayHasKey("summary", $showData);
    }

    /**
     * Test search with special characters in show name
     */
    public function testInvokeWithSpecialCharactersInShowName(): void
    {
        $searchTerms = 'The "Comedians" in Cars Getting Coffee';

        $show = new ApiShow(
            name: 'The "Comedians" in Cars Getting Coffee',
            summary: "Jerry Seinfeld drives around with comedians",
            type: "Reality",
            language: "English",
            status: ShowStatusEnum::RUNNING,
            id: 8620,
            averageRuntime: 15,
            premiered: new \DateTimeImmutable("2012-07-12"),
            ended: null,
            image: "comedians.jpg",
            genres: ["Comedy"],
            theTvDbId: 269169,
            imdbId: "tt2159940",
            episodes: [],
        );

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($searchTerms)
            ->willReturn([$show]);

        $response = $this->controller->__invoke(
            $searchTerms,
            $this->useCaseMock,
        );

        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(1, $responseData);
        $this->assertSame(
            'The "Comedians" in Cars Getting Coffee',
            $responseData[0]["name"],
        );
    }

    /**
     * Test search with show data having null optional fields
     */
    public function testInvokeWithShowDataHavingNullOptionalFields(): void
    {
        $searchTerms = "Minimal Show";

        $show = new ApiShow(
            name: "Minimal Show",
            summary: "A minimal show",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::RUNNING,
            id: 999,
            averageRuntime: null,
            premiered: new \DateTimeImmutable("2020-01-01"),
            ended: null,
            image: null,
            genres: ["Drama"],
            theTvDbId: null,
            imdbId: null,
            episodes: [],
        );

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($searchTerms)
            ->willReturn([$show]);

        $response = $this->controller->__invoke(
            $searchTerms,
            $this->useCaseMock,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(1, $responseData);

        $showData = $responseData[0];
        $this->assertSame("Minimal Show", $showData["name"]);
        $this->assertNull($showData["averageRuntime"] ?? null);
        $this->assertNull($showData["ended"] ?? null);
        $this->assertNull($showData["image"] ?? null);
    }

    /**
     * Test use case exception is propagated
     */
    public function testInvokeThrowsShowsNotFoundExceptionFromUseCase(): void
    {
        $searchTerms = "ErrorShow";

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($searchTerms)
            ->willThrowException(new ShowsNotFoundException());

        $this->expectException(ShowsNotFoundException::class);

        $this->controller->__invoke($searchTerms, $this->useCaseMock);
    }

    /**
     * Test response status code is HTTP 200 OK
     */
    public function testInvokeResponseStatusCodeIsOk(): void
    {
        $searchTerms = "Test Show";

        $show = new ApiShow(
            name: "Test Show",
            summary: "Test summary",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::RUNNING,
            id: 1,
            averageRuntime: 45,
            premiered: new \DateTimeImmutable("2020-01-01"),
            ended: null,
            image: null,
            genres: ["Drama"],
            theTvDbId: null,
            imdbId: null,
            episodes: [],
        );

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($searchTerms)
            ->willReturn([$show]);

        $response = $this->controller->__invoke(
            $searchTerms,
            $this->useCaseMock,
        );

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test response is JSON response instance
     */
    public function testInvokeReturnsJsonResponse(): void
    {
        $searchTerms = "Test Show";

        $show = new ApiShow(
            name: "Test Show",
            summary: "Test summary",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::RUNNING,
            id: 1,
            averageRuntime: 45,
            premiered: new \DateTimeImmutable("2020-01-01"),
            ended: null,
            image: null,
            genres: ["Drama"],
            theTvDbId: null,
            imdbId: null,
            episodes: [],
        );

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->willReturn([$show]);

        $response = $this->controller->__invoke(
            $searchTerms,
            $this->useCaseMock,
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    /**
     * Test controller properly passes search terms to use case
     */
    public function testInvokePassesSearchTermsToUseCase(): void
    {
        $searchTerms = "Exact Search Term";

        $this->useCaseMock
            ->expects($this->once())
            ->method("execute")
            ->with($searchTerms)
            ->willReturn([]);

        $this->controller->__invoke($searchTerms, $this->useCaseMock);
    }

    /**
     * Test multiple calls to controller with different search terms
     */
    public function testInvokeCanBeCalledMultipleTimesWithDifferentTerms(): void
    {
        $searchTerms1 = "Breaking Bad";
        $searchTerms2 = "The Office";

        $show1 = new ApiShow(
            name: "Breaking Bad",
            summary: "Summary 1",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::ENDED,
            id: 1,
            episodes: [],
        );

        $show2 = new ApiShow(
            name: "The Office",
            summary: "Summary 2",
            type: "Scripted",
            language: "English",
            status: ShowStatusEnum::ENDED,
            id: 2,
            episodes: [],
        );

        $this->useCaseMock
            ->expects($this->exactly(2))
            ->method("execute")
            ->willReturnMap([
                [$searchTerms1, [$show1]],
                [$searchTerms2, [$show2]],
            ]);

        $response1 = $this->controller->__invoke(
            $searchTerms1,
            $this->useCaseMock,
        );
        $response2 = $this->controller->__invoke(
            $searchTerms2,
            $this->useCaseMock,
        );

        $data1 = json_decode($response1->getContent(), true);
        $data2 = json_decode($response2->getContent(), true);

        $this->assertSame("Breaking Bad", $data1[0]["name"]);
        $this->assertSame("The Office", $data2[0]["name"]);
    }
}
