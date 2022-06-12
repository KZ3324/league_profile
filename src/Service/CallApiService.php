<?php

namespace App\Service;

use App\Api\LeagueApi;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;
    private $leagueApi;

    public function __construct(HttpClientInterface $client, LeagueApi $leagueApi)
    {
        $this->client = $client;
        $this->leagueApi = $leagueApi;
    }

    public function getChampionData(): array
    {
        $response = $this->client->request(
            'GET',
            'https://euw1.api.riotgames.com/lol/platform/v3/champion-rotations?api_key=' . $this->leagueApi->index() . ''
        );

        return $response->toArray();
    }
    public function getSumonnerInfo($id) : array {
        $response = $this->client->request(
            'GET',
            'https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $id . '?api_key=' . $this->leagueApi->index() . ''
        );
        return $response->toArray();
    }
    public function getChampionMasteries($id) : array {
        $response = $this->client->request(
            'GET',
            'https://euw1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/' . $id . '?api_key=' . $this->leagueApi->index() . ''
        );
        return $response->toArray();
    }

    public function getChampionMasteriesTotal($id) : int {
        $array_pts_masteries = [];
        $i = 0;
        $dataMastaries = $this->getChampionMasteries($id);
        foreach ($dataMastaries as $key => $value) {
            $array_pts_masteries[$i] = $value['championPoints'];
            $i++;
        }

        return array_sum($array_pts_masteries);
    }
}
