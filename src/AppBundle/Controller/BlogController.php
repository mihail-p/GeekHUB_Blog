<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /**
     * @Route("/blog", name="indexBlog")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $nav = 1;
        return $this->render('blog/index.html.twig', ['nav' => $nav
            ]);
    }
}
