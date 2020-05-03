<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(PaginatorInterface $paginator ,Request $request, AdRepository $repo)
    {
        $ads=$paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1),
            6 //nombre d'annoces
        );

            return $this->render('admin/ad/index.html.twig', [
            'ads' => $ads
        ]);
    }
}
