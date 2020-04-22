<?php 

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    /**
     * 
     *
     * @Route("/", name = "homepage")
     */
    function home(AdRepository $adRepo)
    {
        return $this->render('home.html.twig', [
            'ads' => $adRepo->findBestAds(3)
        ]);
    }

}
