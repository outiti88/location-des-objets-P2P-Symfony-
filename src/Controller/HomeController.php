<?php

namespace App\Controller;

use App\Repository\AdRepository;

use App\Repository\PremiumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * 
     *
     * @Route("/", name = "homepage")
     */
    function home(PaginatorInterface $paginator, AdRepository $adRepo, PremiumRepository $premiumRepo, Request $request)
    {
        $premiums = $premiumRepo->findPremium();
        $ads_premium = array();
        foreach ($premiums as $premium) {
            $ads_premium[] = $premium->getAd();
        }
        $ads_premium = $paginator->paginate(
            $ads_premium,
            $request->query->getInt('page', 1),
            3 //nombre d'annoces
        );
        //dd($adRepo->findBestAds(3));
        return $this->render('home.html.twig', [
            'ads_premium' => $ads_premium,
            'ads' => $adRepo->findBestAds(3)
        ]);
    }
}
