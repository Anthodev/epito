<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\ApiPlatform\UseCase\Show;

use App\Domain\Model\ApiShow\ApiShow;
use App\Domain\Factory\ApiShow\ApiShowFactory;
use App\Infrastructure\ApiPlatform\UseCase\Show\GetShowByNameUseCase;
use App\Infrastructure\Client\ApiInterface;
use App\Infrastructure\Enum\TvmazeRoutesEnum;
use App\Infrastructure\Exception\ShowsNotFoundException;
use App\Shared\Dto\Client\ApiGetSearchShowResponseDto;
use App\Shared\Enum\ShowStatusEnum;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GetShowByNameUseCaseTest extends TestCase
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

    public function testExecuteWithValidNameReturnsArrayOfApiShows(): void
    {
        // Arrange
        $showName = "Star Trek";
        $apiResponse = [
            [
                "score" => 0.9,
                "show" => [
                    "id" => 491,
                    "name" => "Star Trek: The Next Generation",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Action", "Adventure", "Science-Fiction"],
                    "status" => "Ended",
                    "averageRuntime" => 60,
                    "premiered" => "1987-09-28",
                    "ended" => "1994-05-23",
                    "image" => "https://example.com/image.jpg",
                    "summary" => "A great sci-fi show",
                    "externals" => [
                        "thetvdb" => 71470,
                        "imdb" => "tt0092455",
                    ],
                ],
            ],
        ];

        $dto = new ApiGetSearchShowResponseDto(
            id: 491,
            name: "Star Trek: The Next Generation",
            type: "Scripted",
            language: "English",
            genres: ["Action", "Adventure", "Science-Fiction"],
            status: ShowStatusEnum::ENDED,
            summary: "A great sci-fi show",
            averageRuntime: 60,
            premiered: "1987-09-28",
            ended: "1994-05-23",
            image: "https://example.com/image.jpg",
            network: null,
            theTvDbId: 71470,
            imdbId: "tt0092455",
        );

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willReturn($apiResponse);

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
        $this->assertEquals("Star Trek: The Next Generation", $result[0]->name);
        $this->assertEquals("Scripted", $result[0]->type);
        $this->assertEquals("English", $result[0]->language);
        $this->assertEquals(71470, $result[0]->theTvDbId);
        $this->assertEquals("tt0092455", $result[0]->imdbId);
    }

    public function testExecuteWithEmptyResultsReturnsEmptyArray(): void
    {
        // Arrange
        $showName = "Unknown Show";
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

        // Act
        $result = $this->useCase->execute($showName);

        // Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testExecuteWithApiExceptionThrowsShowsNotFoundException(): void
    {
        // Arrange
        $showName = "Error Show";

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willThrowException(new \Exception("API Error"));

        $this->expectException(ShowsNotFoundException::class);

        // Act
        $this->useCase->execute($showName);
    }

    public function testExecuteWithNullPremieredDateHandlesCorrectly(): void
    {
        // Arrange
        $showName = "Show Without Premiere Date";
        $apiResponse = [
            [
                "score" => 0.8,
                "show" => [
                    "id" => 123,
                    "name" => "Test Show",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Drama"],
                    "status" => "Running",
                    "averageRuntime" => 45,
                    "premiered" => null,
                    "ended" => null,
                    "image" => null,
                    "summary" => "A test show",
                    "externals" => [
                        "thetvdb" => null,
                        "imdb" => null,
                    ],
                ],
            ],
        ];

        $dto = new ApiGetSearchShowResponseDto(
            id: 123,
            name: "Test Show",
            type: "Scripted",
            language: "English",
            genres: ["Drama"],
            status: ShowStatusEnum::RUNNING,
            summary: "A test show",
            averageRuntime: 45,
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
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willReturn($apiResponse);

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
        $this->assertEquals("Test Show", $result[0]->name);
        $this->assertNull($result[0]->premiered);
        $this->assertNull($result[0]->ended);
        $this->assertNull($result[0]->theTvDbId);
        $this->assertNull($result[0]->imdbId);
    }

    public function testExecuteWithMultipleResultsReturnsAllShows(): void
    {
        // Arrange
        $showName = "Multiple Shows";
        $apiResponse = [
            [
                "score" => 0.9,
                "show" => [
                    "id" => 1,
                    "name" => "Show 1",
                    "type" => "Scripted",
                    "language" => "English",
                    "genres" => ["Drama"],
                    "status" => "Ended",
                    "averageRuntime" => 60,
                    "premiered" => "2020-01-01",
                    "ended" => "2021-01-01",
                    "image" => "image1.jpg",
                    "summary" => "First show",
                    "externals" => ["thetvdb" => 111, "imdb" => "tt111"],
                ],
            ],
            [
                "score" => 0.8,
                "show" => [
                    "id" => 2,
                    "name" => "Show 2",
                    "type" => "Reality",
                    "language" => "English",
                    "genres" => ["Reality"],
                    "status" => "Running",
                    "averageRuntime" => 30,
                    "premiered" => "2021-01-01",
                    "ended" => null,
                    "image" => "image2.jpg",
                    "summary" => "Second show",
                    "externals" => ["thetvdb" => 222, "imdb" => "tt222"],
                ],
            ],
        ];

        $dto1 = new ApiGetSearchShowResponseDto(
            id: 1,
            name: "Show 1",
            type: "Scripted",
            language: "English",
            genres: ["Drama"],
            status: ShowStatusEnum::ENDED,
            summary: "First show",
            averageRuntime: 60,
            premiered: "2020-01-01",
            ended: "2021-01-01",
            image: "image1.jpg",
            network: null,
            theTvDbId: 111,
            imdbId: "tt111",
        );

        $dto2 = new ApiGetSearchShowResponseDto(
            id: 2,
            name: "Show 2",
            type: "Reality",
            language: "English",
            genres: ["Reality"],
            status: ShowStatusEnum::RUNNING,
            summary: "Second show",
            averageRuntime: 30,
            premiered: "2021-01-01",
            ended: null,
            image: "image2.jpg",
            network: null,
            theTvDbId: 222,
            imdbId: "tt222",
        );

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with($showName, TvmazeRoutesEnum::GET_SEARCH_SHOWS->value)
            ->willReturn($apiResponse);

        $this->serializerMock
            ->expects($this->once())
            ->method("denormalize")
            ->with($apiResponse, ApiGetSearchShowResponseDto::class . "[]")
            ->willReturn([$dto1, $dto2]);

        // Act
        $result = $this->useCase->execute($showName);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals("Show 1", $result[0]->name);
        $this->assertEquals("Show 2", $result[1]->name);
    }

    public function testExecuteCallsApiWithCorrectParameters(): void
    {
        // Arrange
        $showName = "Test Parameters";

        $this->apiMock
            ->expects($this->once())
            ->method("performRequest")
            ->with(
                $this->equalTo($showName),
                $this->equalTo(TvmazeRoutesEnum::GET_SEARCH_SHOWS->value),
            )
            ->willReturn([]);

        $this->serializerMock
            ->expects($this->once())
            ->method("denormalize")
            ->willReturn([]);

        // Act
        $this->useCase->execute($showName);

        // Assert is done in the mock expectations
    }
}
