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
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Integration test to verify the correct handling of TVmaze API response structure
 */
class GetShowByNameUseCaseIntegrationTest extends TestCase
{
    private GetShowByNameUseCase $useCase;
    private ApiInterface $apiMock;
    private SerializerInterface&DenormalizerInterface $serializerMock;

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
     * Test with the real TVmaze API response structure containing score and show objects
     */
    public function testExecuteWithRealTvMazeApiStructure(): void
    {
        // Arrange - Real TVmaze API response structure
        $showName = "Star Trek";
        $apiResponse = [
            [
                "score" => 0.9074305,
                "show" => [
                    "id" => 491,
                    "url" =>
                        "https://www.tvmaze.com/shows/491/star-trek-the-next-generation",
                    "name" => "Star Trek: The Next Generation",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Action", "Adventure", "Science-Fiction"],
                    "status" => "Ended",
                    "runtime" => 60,
                    "averageRuntime" => 60,
                    "premiered" => "1987-09-28",
                    "ended" => "1994-05-23",
                    "officialSite" => null,
                    "schedule" => ["time" => "", "days" => ["Monday"]],
                    "rating" => ["average" => 8.7],
                    "weight" => 98,
                    "network" => [
                        "id" => 72,
                        "name" => "Syndication",
                        "country" => [
                            "name" => "United States",
                            "code" => "US",
                            "timezone" => "America/New_York",
                        ],
                        "officialSite" => null,
                    ],
                    "webChannel" => null,
                    "dvdCountry" => null,
                    "externals" => [
                        "tvrage" => 5337,
                        "thetvdb" => 71470,
                        "imdb" => "tt0092455",
                    ],
                    "image" => [
                        "medium" =>
                            "https://static.tvmaze.com/uploads/images/medium_portrait/496/1242352.jpg",
                        "original" =>
                            "https://static.tvmaze.com/uploads/images/original_untouched/496/1242352.jpg",
                    ],
                    "summary" =>
                        "<p><b>Star Trek: The Next Generation</b> (TNG) focuses on the 24th century adventures of Captain Jean-Luc Picard aboard the U.S.S. Enterprise (NCC-1701-D).</p>",
                    "updated" => 1758141959,
                    "_links" => [
                        "self" => [
                            "href" => "https://api.tvmaze.com/shows/491",
                        ],
                        "previousepisode" => [
                            "href" => "https://api.tvmaze.com/episodes/44633",
                            "name" => "All Good Things... (2)",
                        ],
                    ],
                ],
            ],
        ];

        // The DTO should be created with the flattened structure (serializer should handle the transformation)
        $dto = new ApiGetSearchShowResponseDto(
            id: 491,
            name: "Star Trek: The Next Generation",
            type: "Scripted",
            language: "English",
            genres: ["Action", "Adventure", "Science-Fiction"],
            status: ShowStatusEnum::ENDED,
            summary: "<p><b>Star Trek: The Next Generation</b> (TNG) focuses on the 24th century adventures of Captain Jean-Luc Picard aboard the U.S.S. Enterprise (NCC-1701-D).</p>",
            averageRuntime: 60,
            premiered: "1987-09-28",
            ended: "1994-05-23",
            image: "https://static.tvmaze.com/uploads/images/medium_portrait/496/1242352.jpg",
            network: "Syndication",
            theTvDbId: 71470,
            imdbId: "tt0092455",
        );

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willReturn($apiResponse);

        // The serializer should handle the complex nested structure and flatten it to our DTO
        $this->serializerMock
            ->expects($this->once())
            ->method("denormalize")
            ->with($apiResponse, ApiGetSearchShowResponseDto::class . "[]")
            ->willReturn([$dto]);

        // Act
        $result = $this->useCase->execute($showName);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(ApiShow::class, $result[0]);

        // Verify the data was correctly mapped
        $this->assertEquals("Star Trek: The Next Generation", $result[0]->name);
        $this->assertEquals("Scripted", $result[0]->type);
        $this->assertEquals("English", $result[0]->language);
        $this->assertEquals(
            ["Action", "Adventure", "Science-Fiction"],
            $result[0]->genres,
        );
        $this->assertEquals(ShowStatusEnum::ENDED, $result[0]->status);
        $this->assertEquals(60, $result[0]->averageRuntime);
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            $result[0]->premiered,
        );
        $this->assertEquals(
            "1987-09-28",
            $result[0]->premiered->format("Y-m-d"),
        );
        $this->assertInstanceOf(\DateTimeImmutable::class, $result[0]->ended);
        $this->assertEquals("1994-05-23", $result[0]->ended->format("Y-m-d"));
        $this->assertEquals(
            "https://static.tvmaze.com/uploads/images/medium_portrait/496/1242352.jpg",
            $result[0]->image,
        );
        $this->assertEquals(71470, $result[0]->theTvDbId);
        $this->assertEquals("tt0092455", $result[0]->imdbId);
    }

    /**
     * Test handling of shows without external IDs
     */
    public function testExecuteWithShowMissingExternalIds(): void
    {
        // Arrange
        $showName = "Obscure Show";
        $apiResponse = [
            [
                "score" => 0.5,
                "show" => [
                    "id" => 999,
                    "name" => "Some Obscure Show",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Drama"],
                    "status" => "Ended",
                    "averageRuntime" => 30,
                    "premiered" => "2020-01-01",
                    "ended" => "2020-12-31",
                    "image" => null,
                    "summary" => "An obscure show",
                    "externals" => [
                        "tvrage" => null,
                        "thetvdb" => null,
                        "imdb" => null,
                    ],
                ],
            ],
        ];

        $dto = new ApiGetSearchShowResponseDto(
            id: 999,
            name: "Some Obscure Show",
            type: "Scripted",
            language: "English",
            genres: ["Drama"],
            status: ShowStatusEnum::ENDED,
            summary: "An obscure show",
            averageRuntime: 30,
            premiered: "2020-01-01",
            ended: "2020-12-31",
            image: null,
            network: null,
            theTvDbId: null,
            imdbId: null,
        );

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willReturn($apiResponse);

        $this->serializerMock
            ->expects($this->once())
            ->method("denormalize")
            ->willReturn([$dto]);

        // Act
        $result = $this->useCase->execute($showName);

        // Assert
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->theTvDbId);
        $this->assertNull($result[0]->imdbId);
    }

    /**
     * Test handling of null premiered date from API
     */
    public function testExecuteWithNullPremieredDate(): void
    {
        // Arrange
        $showName = "Show Without Premiere Date";
        $apiResponse = [
            [
                "score" => 0.7,
                "show" => [
                    "id" => 888,
                    "name" => "Future Show",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Sci-Fi"],
                    "status" => "To Be Determined",
                    "averageRuntime" => null,
                    "premiered" => null,
                    "ended" => null,
                    "image" => null,
                    "summary" => "A show without premiere date",
                    "externals" => [
                        "tvrage" => null,
                        "thetvdb" => null,
                        "imdb" => null,
                    ],
                ],
            ],
        ];

        $dto = new ApiGetSearchShowResponseDto(
            id: 888,
            name: "Future Show",
            type: "Scripted",
            language: "English",
            genres: ["Sci-Fi"],
            status: ShowStatusEnum::IN_DEVELOPMENT,
            summary: "A show without premiere date",
            averageRuntime: null,
            premiered: null,
            ended: null,
            image: null,
            network: null,
            theTvDbId: null,
            imdbId: null,
        );

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->willReturn($apiResponse);

        $this->serializerMock
            ->expects($this->once())
            ->method("denormalize")
            ->willReturn([$dto]);

        // Act
        $result = $this->useCase->execute($showName);

        // Assert
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->premiered);
        $this->assertNull($result[0]->ended);
    }

    /**
     * Test that exception is thrown when API fails
     */
    public function testExecuteThrowsShowsNotFoundExceptionOnApiFailure(): void
    {
        // Arrange
        $showName = "Failing Show";

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->willThrowException(new \Exception("API connection failed"));

        $this->expectException(ShowsNotFoundException::class);

        // Act
        $this->useCase->execute($showName);
    }
}
