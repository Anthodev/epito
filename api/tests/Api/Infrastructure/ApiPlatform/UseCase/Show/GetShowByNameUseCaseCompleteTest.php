<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\ApiPlatform\UseCase\Show;

use App\Domain\Model\ApiShow\ApiShow;
use App\Infrastructure\ApiPlatform\UseCase\Show\GetShowByNameUseCase;
use App\Infrastructure\Client\ApiInterface;
use App\Infrastructure\Enum\TvmazeRoutesEnum;
use App\Infrastructure\Exception\ShowsNotFoundException;
use App\Shared\Dto\Client\ApiGetSearchShowResponseDto;
use App\Shared\Enum\ShowStatusEnum;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Complete test suite for GetShowByNameUseCase
 * Tests all edge cases and scenarios
 */
class GetShowByNameUseCaseCompleteTest extends TestCase
{
    private GetShowByNameUseCase $useCase;
    private ApiInterface&MockObject $apiMock;
    private SerializerInterface&DenormalizerInterface&MockObject $serializerMock;

    protected function setUp(): void
    {
        $this->apiMock = $this->createMock(ApiInterface::class);
        $this->serializerMock = $this->createMockForIntersectionOfInterfaces([
            SerializerInterface::class,
            DenormalizerInterface::class,
        ]);
        $this->useCase = new GetShowByNameUseCase(
            $this->apiMock,
            $this->serializerMock,
        );
    }

    /**
     * Test successful execution with complete data
     */
    public function testSuccessfulExecutionWithCompleteData(): void
    {
        $showName = "Breaking Bad";
        $apiResponse = $this->getCompleteApiResponse();

        $dto = new ApiGetSearchShowResponseDto(
            id: 169,
            name: "Breaking Bad",
            type: "Scripted",
            language: "English",
            genres: ["Crime", "Drama", "Thriller"],
            status: ShowStatusEnum::ENDED,
            summary: "A high school chemistry teacher turned methamphetamine manufacturer",
            averageRuntime: 47,
            premiered: "2008-01-20",
            ended: "2013-09-29",
            image: "https://static.tvmaze.com/uploads/images/medium_portrait/0/2400.jpg",
            network: "AMC",
            theTvDbId: 81189,
            imdbId: "tt0903747",
        );

        $this->setupMocks($showName, $apiResponse, [$dto]);

        $result = $this->useCase->execute($showName);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(ApiShow::class, $result[0]);
        $this->assertCompleteShowData($result[0]);
    }

    /**
     * Test with multiple shows having different data completeness
     */
    public function testMultipleShowsWithDifferentDataCompleteness(): void
    {
        $showName = "Test";
        $apiResponse = [
            $this->getCompleteShowData(),
            $this->getMinimalShowData(),
            $this->getShowWithNullValues(),
        ];

        $dtos = [
            $this->createCompleteDto(),
            $this->createMinimalDto(),
            $this->createNullValuesDto(),
        ];

        $this->setupMocks($showName, $apiResponse, $dtos);

        $result = $this->useCase->execute($showName);

        $this->assertCount(3, $result);

        // First show - complete data
        $this->assertCompleteShowData($result[0]);

        // Second show - minimal data
        $this->assertMinimalShowData($result[1]);

        // Third show - null values
        $this->assertNullValuesShowData($result[2]);
    }

    /**
     * Test empty search results
     */
    public function testEmptySearchResults(): void
    {
        $showName = "NonExistentShow12345";
        $apiResponse = [];

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willReturn($apiResponse);

        $this->serializerMock
            ->expects($this->once())
            ->method("denormalize")
            ->with($apiResponse, ApiGetSearchShowResponseDto::class . "[]")
            ->willReturn([]);

        $result = $this->useCase->execute($showName);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test API exception handling
     */
    public function testApiExceptionThrowsShowsNotFoundException(): void
    {
        $showName = "ErrorShow";
        $exception = new \Exception("API connection failed");

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willThrowException($exception);

        $this->expectException(ShowsNotFoundException::class);

        $this->useCase->execute($showName);
    }

    /**
     * Test handling of shows with special characters in names
     */
    public function testSpecialCharactersInShowNames(): void
    {
        $showName = 'It\'s Always Sunny in Philadelphia';
        $apiResponse = [
            [
                "score" => 0.95,
                "show" => [
                    "id" => 299,
                    "name" => 'It\'s Always Sunny in Philadelphia',
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Comedy"],
                    "status" => "Running",
                    "averageRuntime" => 22,
                    "premiered" => "2005-08-04",
                    "ended" => null,
                    "externals" => ["thetvdb" => 76703, "imdb" => "tt0472954"],
                    "image" => [
                        "medium" => "sunny.jpg",
                        "original" => "sunny-large.jpg",
                    ],
                    "summary" => "Four friends run a bar together",
                ],
            ],
        ];

        $dto = new ApiGetSearchShowResponseDto(
            id: 299,
            name: 'It\'s Always Sunny in Philadelphia',
            type: "Scripted",
            language: "English",
            genres: ["Comedy"],
            status: ShowStatusEnum::RUNNING,
            summary: "Four friends run a bar together",
            averageRuntime: 22,
            premiered: "2005-08-04",
            ended: null,
            image: "sunny.jpg",
            network: null,
            theTvDbId: 76703,
            imdbId: "tt0472954",
        );

        $this->setupMocks($showName, $apiResponse, [$dto]);

        $result = $this->useCase->execute($showName);

        $this->assertCount(1, $result);
        $this->assertEquals(
            'It\'s Always Sunny in Philadelphia',
            $result[0]->name,
        );
        $this->assertEquals(ShowStatusEnum::RUNNING, $result[0]->status);
        $this->assertNull($result[0]->ended);
    }

    /**
     * Test handling of very long show names and summaries
     */
    public function testLongShowNamesAndSummaries(): void
    {
        $showName = "The Adventures of Jimmy Neutron: Boy Genius";
        $longSummary = str_repeat("This is a very long summary. ", 50);

        $apiResponse = [
            [
                "score" => 0.8,
                "show" => [
                    "id" => 555,
                    "name" => $showName,
                    "type" => "Animation",
                    "language" => "English",
                    "genres" => ["Animation", "Children"],
                    "status" => "Ended",
                    "averageRuntime" => 22,
                    "premiered" => "2002-07-20",
                    "ended" => "2006-11-25",
                    "externals" => ["thetvdb" => 78869, "imdb" => "tt0337792"],
                    "image" => null,
                    "summary" => $longSummary,
                ],
            ],
        ];

        $dto = new ApiGetSearchShowResponseDto(
            id: 555,
            name: $showName,
            type: "Animation",
            language: "English",
            genres: ["Animation", "Children"],
            status: ShowStatusEnum::ENDED,
            summary: $longSummary,
            averageRuntime: 22,
            premiered: "2002-07-20",
            ended: "2006-11-25",
            image: null,
            network: null,
            theTvDbId: 78869,
            imdbId: "tt0337792",
        );

        $this->setupMocks($showName, $apiResponse, [$dto]);

        $result = $this->useCase->execute($showName);

        $this->assertCount(1, $result);
        $this->assertEquals($showName, $result[0]->name);
        $this->assertEquals($longSummary, $result[0]->summary);
    }

    /**
     * Test edge case: show with same name but different years
     */
    public function testShowsWithSameNameDifferentYears(): void
    {
        $showName = "The Flash";
        $apiResponse = [
            [
                "score" => 0.95,
                "show" => [
                    "id" => 13,
                    "name" => "The Flash",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Drama", "Science-Fiction"],
                    "status" => "Ended",
                    "averageRuntime" => 44,
                    "premiered" => "1990-09-20",
                    "ended" => "1991-05-18",
                    "externals" => ["thetvdb" => 77765, "imdb" => "tt0098798"],
                    "image" => null,
                    "summary" => "The original Flash series",
                ],
            ],
            [
                "score" => 0.9,
                "show" => [
                    "id" => 279,
                    "name" => "The Flash",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Drama", "Science-Fiction"],
                    "status" => "Ended",
                    "averageRuntime" => 44,
                    "premiered" => "2014-10-07",
                    "ended" => "2023-05-24",
                    "externals" => ["thetvdb" => 279121, "imdb" => "tt3107288"],
                    "image" => null,
                    "summary" => "The modern Flash series",
                ],
            ],
        ];

        $dto1 = new ApiGetSearchShowResponseDto(
            id: 13,
            name: "The Flash",
            type: "Scripted",
            language: "English",
            genres: ["Drama", "Science-Fiction"],
            status: ShowStatusEnum::ENDED,
            summary: "The original Flash series",
            averageRuntime: 44,
            premiered: "1990-09-20",
            ended: "1991-05-18",
            image: null,
            network: null,
            theTvDbId: 77765,
            imdbId: "tt0098798",
        );

        $dto2 = new ApiGetSearchShowResponseDto(
            id: 279,
            name: "The Flash",
            type: "Scripted",
            language: "English",
            genres: ["Drama", "Science-Fiction"],
            status: ShowStatusEnum::ENDED,
            summary: "The modern Flash series",
            averageRuntime: 44,
            premiered: "2014-10-07",
            ended: "2023-05-24",
            image: null,
            network: null,
            theTvDbId: 279121,
            imdbId: "tt3107288",
        );

        $this->setupMocks($showName, $apiResponse, [$dto1, $dto2]);

        $result = $this->useCase->execute($showName);

        $this->assertCount(2, $result);

        // Both shows should have the same name but different premiere dates
        $this->assertEquals("The Flash", $result[0]->name);
        $this->assertEquals("The Flash", $result[1]->name);

        $this->assertEquals(
            "1990-09-20",
            $result[0]->premiered->format("Y-m-d"),
        );
        $this->assertEquals(
            "2014-10-07",
            $result[1]->premiered->format("Y-m-d"),
        );
    }

    // Helper methods

    private function setupMocks(
        string $showName,
        array $apiResponse,
        array $dtos,
    ): void {
        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willReturn($apiResponse);

        $this->serializerMock
            ->expects($this->once())
            ->method("denormalize")
            ->with($apiResponse, ApiGetSearchShowResponseDto::class . "[]")
            ->willReturn($dtos);
    }

    private function getCompleteApiResponse(): array
    {
        return [
            [
                "score" => 0.98,
                "show" => [
                    "id" => 169,
                    "name" => "Breaking Bad",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Crime", "Drama", "Thriller"],
                    "status" => "Ended",
                    "averageRuntime" => 47,
                    "premiered" => "2008-01-20",
                    "ended" => "2013-09-29",
                    "externals" => ["thetvdb" => 81189, "imdb" => "tt0903747"],
                    "image" => [
                        "medium" => "breaking-bad.jpg",
                        "original" => "breaking-bad-large.jpg",
                    ],
                    "summary" =>
                        "A high school chemistry teacher turned methamphetamine manufacturer",
                ],
            ],
        ];
    }

    private function getCompleteShowData(): array
    {
        return [
            "score" => 0.98,
            "show" => [
                "id" => 169,
                "name" => "Breaking Bad",
                "type" => "Scripted",
                "language" => "English",
                "genres" => ["Crime", "Drama", "Thriller"],
                "status" => "Ended",
                "averageRuntime" => 47,
                "premiered" => "2008-01-20",
                "ended" => "2013-09-29",
                "externals" => ["thetvdb" => 81189, "imdb" => "tt0903747"],
                "image" => [
                    "medium" => "breaking-bad.jpg",
                    "original" => "breaking-bad-large.jpg",
                ],
                "summary" =>
                    "A high school chemistry teacher turned methamphetamine manufacturer",
            ],
        ];
    }

    private function getMinimalShowData(): array
    {
        return [
            "score" => 0.7,
            "show" => [
                "id" => 999,
                "name" => "Minimal Show",
                "type" => "Scripted",
                "language" => "English",
                "genres" => ["Drama"],
                "status" => "Running",
                "averageRuntime" => null,
                "premiered" => "2020-01-01",
                "ended" => null,
                "externals" => ["thetvdb" => null, "imdb" => null],
                "image" => null,
                "summary" => "A minimal show",
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getShowWithNullValues(): array
    {
        return [
            "score" => 0.5,
            "show" => [
                "id" => 888,
                "name" => "Null Show",
                "type" => "Scripted",
                "language" => "English",
                "genres" => [],
                "status" => "To Be Determined",
                "averageRuntime" => null,
                "premiered" => null,
                "ended" => null,
                "externals" => ["thetvdb" => null, "imdb" => null],
                "image" => null,
                "summary" => "",
            ],
        ];
    }

    private function createCompleteDto(): ApiGetSearchShowResponseDto
    {
        return new ApiGetSearchShowResponseDto(
            id: 169,
            name: "Breaking Bad",
            type: "Scripted",
            language: "English",
            genres: ["Crime", "Drama", "Thriller"],
            status: ShowStatusEnum::ENDED,
            summary: "A high school chemistry teacher turned methamphetamine manufacturer",
            averageRuntime: 47,
            premiered: "2008-01-20",
            ended: "2013-09-29",
            image: "https://static.tvmaze.com/uploads/images/medium_portrait/0/2400.jpg",
            network: "AMC",
            theTvDbId: 81189,
            imdbId: "tt0903747",
        );
    }

    private function createMinimalDto(): ApiGetSearchShowResponseDto
    {
        return new ApiGetSearchShowResponseDto(
            id: 999,
            name: "Minimal Show",
            type: "Scripted",
            language: "English",
            genres: ["Drama"],
            status: ShowStatusEnum::RUNNING,
            summary: "A minimal show",
            averageRuntime: null,
            premiered: "2020-01-01",
            ended: null,
            image: null,
            network: null,
            theTvDbId: null,
            imdbId: null,
        );
    }

    private function createNullValuesDto(): ApiGetSearchShowResponseDto
    {
        return new ApiGetSearchShowResponseDto(
            id: 888,
            name: "Null Show",
            type: "Scripted",
            language: "English",
            genres: [],
            status: ShowStatusEnum::IN_DEVELOPMENT,
            summary: "",
            averageRuntime: null,
            premiered: null,
            ended: null,
            image: null,
            network: null,
            theTvDbId: null,
            imdbId: null,
        );
    }

    private function assertCompleteShowData(ApiShow $show): void
    {
        $this->assertEquals("Breaking Bad", $show->name);
        $this->assertEquals("Scripted", $show->type);
        $this->assertEquals("English", $show->language);
        $this->assertEquals(["Crime", "Drama", "Thriller"], $show->genres);
        $this->assertEquals(ShowStatusEnum::ENDED, $show->status);
        $this->assertEquals(47, $show->averageRuntime);
        $this->assertInstanceOf(\DateTimeImmutable::class, $show->premiered);
        $this->assertEquals("2008-01-20", $show->premiered->format("Y-m-d"));
        $this->assertInstanceOf(\DateTimeImmutable::class, $show->ended);
        $this->assertEquals("2013-09-29", $show->ended->format("Y-m-d"));
        $this->assertEquals(
            "https://static.tvmaze.com/uploads/images/medium_portrait/0/2400.jpg",
            $show->image,
        );
        $this->assertEquals(81189, $show->theTvDbId);
        $this->assertEquals("tt0903747", $show->imdbId);
        $this->assertIsArray($show->episodes);
        $this->assertEmpty($show->episodes);
    }

    private function assertMinimalShowData(ApiShow $show): void
    {
        $this->assertEquals("Minimal Show", $show->name);
        $this->assertEquals("Scripted", $show->type);
        $this->assertEquals("English", $show->language);
        $this->assertEquals(["Drama"], $show->genres);
        $this->assertEquals(ShowStatusEnum::RUNNING, $show->status);
        $this->assertNull($show->averageRuntime);
        $this->assertInstanceOf(\DateTimeImmutable::class, $show->premiered);
        $this->assertEquals("2020-01-01", $show->premiered->format("Y-m-d"));
        $this->assertNull($show->ended);
        $this->assertNull($show->image);
        $this->assertNull($show->theTvDbId);
        $this->assertNull($show->imdbId);
    }

    private function assertNullValuesShowData(ApiShow $show): void
    {
        $this->assertEquals("Null Show", $show->name);
        $this->assertEquals("Scripted", $show->type);
        $this->assertEquals("English", $show->language);
        $this->assertEquals([], $show->genres);
        $this->assertEquals(ShowStatusEnum::IN_DEVELOPMENT, $show->status);
        $this->assertNull($show->averageRuntime);
        $this->assertNull($show->premiered);
        $this->assertNull($show->ended);
        $this->assertNull($show->image);
        $this->assertNull($show->theTvDbId);
        $this->assertNull($show->imdbId);
        $this->assertEquals("", $show->summary);
    }
}
