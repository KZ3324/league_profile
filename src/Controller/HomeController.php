<?php

namespace App\Controller;

use App\Form\SumonnerNameType;
use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CallApiService $callApiService): Response
    {

        $form = $this->createForm(SumonnerNameType::class);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
