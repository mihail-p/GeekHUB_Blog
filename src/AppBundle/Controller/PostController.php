<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/10/16
 * Time: 10:41 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Authors;
use AppBundle\Entity\Posts;
use AppBundle\Form\PostAdd;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/postAdd", name="postAdd")
     */
    public function addAction(Request $request)
    {
        $post = new Posts();
        $post->setTitle('Title_1');
        $post->setDateTime(new \DateTime());

        $form = $this->createForm(new PostAdd(), $post);
        $form->add('add post', SubmitType::class);

        $form->handleRequest($request);
        $nav = 4;
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->render(':blog:addAuthorOk.html.twig', ['nav' => $nav]);
        }

        return $this->render(':blog:addAuthor.html.twig', [
            'form' => $form->createView(), 'nav' => $nav
        ]);
    }
    /**
     * @Route("/postList", name="postList")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Authors')
            ->getAllAuthors();
        $nav = 3;

        return $this->render(':blog:listAuthor.html.twig', [
            'listObj' => $listObj, 'nav' => $nav
        ]);
    }

}