<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\PremiumRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * 
     *
     * @Route("/", name = "homepage")
     */
    function home(PremiumRepository $premiumRepo)
    {
        $premiums = $premiumRepo->findBestAds(3);
        $ads = array();
        foreach ($premiums as $premium) {
            $ads[] = $premium->getAd();
        }
        return $this->render('home.html.twig', [
            'ads' => $ads
        ]);
    }
}
