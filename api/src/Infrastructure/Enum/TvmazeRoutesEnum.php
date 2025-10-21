<?php

declare(strict_types=1);

namespace App\Infrastructure\Enum;

enum TvmazeRoutesEnum: string
{
    case GET_SEARCH_SHOWS = '/search/shows?q=%s';
    case GET_SHOW = '/shows/%d';
    case GET_SHOW_WITH_CAST = '/shows/%d/cast';
    case GET_SHOW_COMPLETE_DATA = '/shows/%d?embed[]=seasons&embed[]=episodes&embed[]=cast';
    case GET_SHOW_FIRST_EPISODE = '/shows/%d/episodebynumber?season=1&number=1';
    case GET_SHOW_EPISODES = '/shows/%d/episodes';
}
