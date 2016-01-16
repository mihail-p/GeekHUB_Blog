<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/10/16
 * Time: 10:41 AM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Post;
use AppBundle\Form\PostAddType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PostController
 * @Route("/admin/post")
 */

class PostController extends Controller
{
    /**
     * @Route("/add", name="postAdd")
     */
    public function addAction(Request $request)
    {
        $post = new Post();
        $post->setTitle('Title_1');
        $post->setDateTime(new \DateTime());

        $form = $this->createForm(new PostAddType(), $post);
        $form->add('add post', SubmitType::class);

        $form->handleRequest($request);
        $nav = 4;
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->render(':blog:addItemOk.html.twig', ['nav' => $nav]);
        }

        return $this->render(':blog/Admin:addItem.html.twig', [
            'form' => $form->createView(), 'nav' => $nav
        ]);
    }
    /**
     * @Route("/list", name="admPostList")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Post')
            ->getPosts();
        $nav = 9;

        return $this->render(':blog/Admin:admListPosts.html.twig', [
            'listObj' => $listObj, 'nav' => $nav
        ]);
    }
    /**
     *@Route("/mod/{id}", name="admPostMod",
     *     requirements={"id": "\d+"})
     */
    public function modAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postObj = $em->getRepository('AppBundle:Post')->find($id);
        $form = $this->createForm(PostAddType::class, $postObj);
        $form->add('modify', SubmitType::class);
        $nav = 10;

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            /*$formString = $form->getData()->getTeam()->getCountry(); */

            if ($form->isValid()) {
                /*  $formData = $form->getData(); */
                $em->flush();

                return $this->render('blog/addItemOk.html.twig',['nav' => $nav]);
            }
        }
        return $this->render(':blog/Admin:addItem.html.twig',
            ['form' => $form->createView(),'nav' => $nav]);
    }
}