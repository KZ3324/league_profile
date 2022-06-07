<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AfficherProfilleController extends AbstractController
{
    #[Route('/profile/', name: 'app_afficher_profille')]
    public function index(CallApiService $callApiService): Response
    {
        $name = $_GET['sumonner_name']['sumonnerName'];

        // Requetes pour récupere informations du sumonner

        $req = $callApiService->getSumonnerInfo($name);

        // Requetes pour informations champions masteries

        $dataMastaries = $callApiService->getChampionMasteries($req['id']);


        // Récupérer le totaux points masteries 

        $ptsMasteries = $callApiService->getChampionMasteriesTotal($req['id']);

        // Récupérer les nom des champion par leur id

        $listeChampion = $this->listeAllChampions();

        // Récupérer les images des champions

        $imagePremier = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" . str_replace("'",'',str_replace(' ', '', $listeChampion[$dataMastaries[0]['championId']])) . "_0.jpg";
        $imageDeuxieme = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" . str_replace("'",'',str_replace(' ', '', $listeChampion[$dataMastaries[1]['championId']])) . "_0.jpg";
        $imageTroisieme = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" . str_replace("'",'',str_replace(' ', '', $listeChampion[$dataMastaries[2]['championId']]))  . "_0.jpg";

        return $this->render('afficher_profille/index.html.twig', [
            'name' => $name,
            'level' => $req['summonerLevel'],
            'ptsmaitrise' =>  number_format($ptsMasteries),
            'dataMasteries' => $dataMastaries,
            'champion' => [
                'premier' => [
                    'nom' => $listeChampion[$dataMastaries[0]['championId']],
                    'masteries' => number_format($dataMastaries[0]['championPoints']),
                    'image' => $imagePremier
                ],
                'deuxieme' => [
                    'nom' => $listeChampion[$dataMastaries[1]['championId']],
                    'masteries' => number_format($dataMastaries[1]['championPoints']),
                    'image' => $imageDeuxieme
                ],
                'troisieme' => [
                    'nom' => $listeChampion[$dataMastaries[2]['championId']],
                    'masteries' => number_format($dataMastaries[2]['championPoints']),
                    'image' => $imageTroisieme
                ],
            ],
        ]);
        
    }

    public function listeAllChampions() {
        $json = file_get_contents("http://ddragon.leagueoflegends.com/cdn/12.10.1/data/en_US/champion.json");
        $list_json = json_decode($json, true)['data'];

        $i = 0;
        $liste = array();
        foreach ($list_json as $key => $value) {
            $liste[$value['key']] = $value['name'];
            $i++;
        }
        return $liste;
    }
}
