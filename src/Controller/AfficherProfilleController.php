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
        $level = $req['summonerLevel'];

        // Requetes pour informations champions masteries

        $dataMastaries = $callApiService->getChampionMasteries($req['id']);


        // Récupérer le totaux points masteries 

        $array_pts_masteries = [];
        $i = 0;
        foreach ($dataMastaries as $key => $value) {
            $array_pts_masteries[$i] = $value['championPoints'];
            $i++;
        }

        $ptsMasteries = array_sum($array_pts_masteries);

        // Récupérer les nom des champion par leur id

        $json = file_get_contents("http://ddragon.leagueoflegends.com/cdn/12.10.1/data/en_US/champion.json");
        $list = json_decode($json, true)['data'];

        // dd($list);

        $i = 0;
        $liste2 = array();
        foreach ($list as $key => $value) {
            $liste2[$value['key']] = $value['name'];
            $i++;
        }

        // dd($liste2);

        // Récupérer les images des champions

        $imagePremier = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" . str_replace("'",'',str_replace(' ', '', $liste2[$dataMastaries[0]['championId']])) . "_0.jpg";
        $imageDeuxieme = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" . str_replace("'",'',str_replace(' ', '', $liste2[$dataMastaries[1]['championId']])) . "_0.jpg";
        $imageTroisieme = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/" . str_replace("'",'',str_replace(' ', '', $liste2[$dataMastaries[2]['championId']]))  . "_0.jpg";


        // dd($dataMastaries[0]['championPoints']);


        return $this->render('afficher_profille/index.html.twig', [
            'name' => $name,
            'level' => $level,
            'ptsmaitrise' =>  number_format($ptsMasteries),
            'dataMasteries' => $dataMastaries,
            'champion' => [
                'premier' => [
                    'nom' => $liste2[$dataMastaries[0]['championId']],
                    'masteries' => number_format($dataMastaries[0]['championPoints']),
                    'image' => $imagePremier
                ],
                'deuxieme' => [
                    'nom' => $liste2[$dataMastaries[1]['championId']],
                    'masteries' => number_format($dataMastaries[1]['championPoints']),
                    'image' => $imageDeuxieme
                ],
                'troisieme' => [
                    'nom' => $liste2[$dataMastaries[2]['championId']],
                    'masteries' => number_format($dataMastaries[2]['championPoints']),
                    'image' => $imageTroisieme
                ],
            ],
        ]);
    }
}
