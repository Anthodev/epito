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
 * Test for TVmaze API response structure handling
 */
class TvmazeApiResponseHandlingTest extends TestCase
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
     * Test with real TVmaze API response structure - the serializer should handle the transformation
     */
    public function testRealTvMazeApiResponseStructure(): void
    {
        // Arrange - Real TVmaze API response with score and nested show object
        $showName = "Star Trek";
        $apiResponse = [
            [
                "score" => 0.9074305,
                "show" => [
                    "id" => 491,
                    "name" => "Star Trek: The Next Generation",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Action", "Adventure", "Science-Fiction"],
                    "status" => "Ended",
                    "runtime" => 60,
                    "averageRuntime" => 60,
                    "premiered" => "1987-09-28",
                    "ended" => "1994-05-23",
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
                ],
            ],
        ];

        // The serializer should transform the nested structure to our flat DTO
        $expectedDto = new ApiGetSearchShowResponseDto(
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
            network: null,
            theTvDbId: 71470,
            imdbId: "tt0092455",
        );

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willReturn($apiResponse);

        // The serializer is responsible for transforming the complex nested structure
        $this->serializerMock
            ->expects($this->once())
            ->method("denormalize")
            ->with(
                [$apiResponse[0]["show"]],
                ApiGetSearchShowResponseDto::class . "[]",
            )
            ->willReturn([$expectedDto]);

        // Act
        $result = $this->useCase->execute($showName);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(ApiShow::class, $result[0]);

        // Verify all data is correctly mapped
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
     * Test handling of multiple shows in response
     */
    public function testMultipleShowsInResponse(): void
    {
        // Arrange
        $showName = "Girls";
        $apiResponse = [
            [
                "score" => 0.95,
                "show" => [
                    "id" => 139,
                    "name" => "Girls",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Drama", "Romance"],
                    "status" => "Ended",
                    "averageRuntime" => 30,
                    "premiered" => "2012-04-15",
                    "ended" => "2017-04-16",
                    "externals" => ["thetvdb" => 220411, "imdb" => "tt1723816"],
                    "image" => [
                        "medium" => "image1.jpg",
                        "original" => "image1-large.jpg",
                    ],
                    "summary" =>
                        "A comic look at the humiliations of girls in their 20s",
                ],
            ],
            [
                "score" => 0.8663825,
                "show" => [
                    "id" => 41734,
                    "name" => "GIRLS",
                    "type" => "Scripted",
                    "language" => "Mongolian",
                    "genres" => ["Comedy"],
                    "status" => "Ended",
                    "averageRuntime" => 41,
                    "premiered" => "2018-06-15",
                    "ended" => "2019-10-14",
                    "externals" => ["thetvdb" => null, "imdb" => "tt8709752"],
                    "image" => [
                        "medium" => "image2.jpg",
                        "original" => "image2-large.jpg",
                    ],
                    "summary" => "Mongolian comedy show",
                ],
            ],
        ];

        $dto1 = new ApiGetSearchShowResponseDto(
            id: 139,
            name: "Girls",
            type: "Scripted",
            language: "English",
            genres: ["Drama", "Romance"],
            status: ShowStatusEnum::ENDED,
            summary: "A comic look at the humiliations of girls in their 20s",
            averageRuntime: 30,
            premiered: "2012-04-15",
            ended: "2017-04-16",
            image: "image1.jpg",
            network: null,
            theTvDbId: 220411,
            imdbId: "tt1723816",
        );

        $dto2 = new ApiGetSearchShowResponseDto(
            id: 41734,
            name: "GIRLS",
            type: "Scripted",
            language: "Mongolian",
            genres: ["Comedy"],
            status: ShowStatusEnum::ENDED,
            summary: "Mongolian comedy show",
            averageRuntime: 41,
            premiered: "2018-06-15",
            ended: "2019-10-14",
            image: "image2.jpg",
            network: null,
            theTvDbId: null,
            imdbId: "tt8709752",
        );

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->willReturn($apiResponse);

        $this->serializerMock
            ->expects($this->once())
            ->method("denormalize")
            ->willReturn([$dto1, $dto2]);

        // Act
        $result = $this->useCase->execute($showName);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        // First show
        $this->assertEquals("Girls", $result[0]->name);
        $this->assertEquals("English", $result[0]->language);
        $this->assertEquals(["Drama", "Romance"], $result[0]->genres);
        $this->assertEquals(220411, $result[0]->theTvDbId);
        $this->assertEquals("tt1723816", $result[0]->imdbId);

        // Second show
        $this->assertEquals("GIRLS", $result[1]->name);
        $this->assertEquals("Mongolian", $result[1]->language);
        $this->assertEquals(["Comedy"], $result[1]->genres);
        $this->assertNull($result[1]->theTvDbId);
        $this->assertEquals("tt8709752", $result[1]->imdbId);
    }

    /**
     * Test handling of shows with minimal data
     */
    public function testShowWithMinimalData(): void
    {
        // Arrange
        $showName = "Minimal Show";
        $apiResponse = [
            [
                "score" => 0.5,
                "show" => [
                    "id" => 123,
                    "name" => "Minimal Show",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => [],
                    "status" => "To Be Determined",
                    "averageRuntime" => null,
                    "premiered" => null,
                    "ended" => null,
                    "image" => null,
                    "summary" => "",
                    "externals" => [
                        "tvrage" => null,
                        "thetvdb" => null,
                        "imdb" => null,
                    ],
                ],
            ],
        ];

        $dto = new ApiGetSearchShowResponseDto(
            id: 123,
            name: "Minimal Show",
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
        $this->assertEquals("Minimal Show", $result[0]->name);
        $this->assertEmpty($result[0]->genres);
        $this->assertNull($result[0]->averageRuntime);
        $this->assertNull($result[0]->premiered);
        $this->assertNull($result[0]->ended);
        $this->assertNull($result[0]->image);
        $this->assertNull($result[0]->theTvDbId);
        $this->assertNull($result[0]->imdbId);
    }

    /**
     * Test exception handling when API fails
     */
    public function testApiFailureHandling(): void
    {
        // Arrange
        $showName = "Failing Show";

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->willThrowException(new \Exception("Network error"));

        $this->expectException(ShowsNotFoundException::class);

        // Act
        $this->useCase->execute($showName);
    }
}
