<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getChampionData(): array
    {
        $response = $this->client->request(
            'GET',
            'https://euw1.api.riotgames.com/lol/platform/v3/champion-rotations?api_key=RGAPI-4f5fd8b4-6394-4b1b-87d3-682cd804c0d5'
        );

        return $response->toArray();
    }
    public function getSumonnerInfo($id) : array {
        $response = $this->client->request(
            'GET',
            'https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $id . '?api_key=RGAPI-4f5fd8b4-6394-4b1b-87d3-682cd804c0d5'
        );
        return $response->toArray();
    }
    public function getChampionMasteries($id) : array {
        $response = $this->client->request(
            'GET',
            'https://euw1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/' . $id . '?api_key=RGAPI-4f5fd8b4-6394-4b1b-87d3-682cd804c0d5'
        );
        return $response->toArray();
    }
}
