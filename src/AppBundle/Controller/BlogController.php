<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    /**
     * @Route("/", name="indexBlog")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $nav = 1;
        return $this->render('blog/index.html.twig', ['nav' => $nav
            ]);
    }
    /**
     * @Route("/admin", name="adminBlog")
     */
    public function listAdminAction(Request $request)
    {
        // replace this example code with whatever you need
        $nav = 8;
        return $this->render(':blog/Admin:index.html.twig', ['nav' => $nav
            ]);
    }
    /**
     * @Route("/list", name="postList")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Post')
            ->getPosts();
        $nav = 5;

        return $this->render(':blog:listPosts.html.twig', [
            'listObj' => $listObj, 'nav' => $nav
        ]);
    }
    /**
     * @param $slug
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     * @Route("/post.show/{slug}", name="showOnePost", requirements={"slug" = "[a-z1-9\-_\/]+"})
     */
    public function showPostAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Post')->findBy(['slug' => $slug]);
        $nav = 0;

        return $this->render(':blog:listPosts.html.twig', ['listObj' => $listObj, 'nav' => $nav]);
    }
}
