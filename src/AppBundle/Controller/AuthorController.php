<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/10/16
 * Time: 10:41 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends Controller
{
    /**
     * @Route("/authorAdd", name="authorAdd")
     */
    public function addAction(Request $request)
    {
        $author = new Author();
        $author->setAuthor('Author1');
        $author->setDateTime(new \DateTime());

        $form = $this->createFormBuilder($author)
            ->add('Author', TextType::class)
            ->add('Passw', PasswordType::class)
            ->add('DateTime', DateTimeType::class)
            ->add('Add', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        $nav = 2;
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->render(':blog:addAuthorOk.html.twig', ['nav' => $nav]);
        }

        return $this->render(':blog:addAuthor.html.twig', [
            'form' => $form->createView(), 'nav' => $nav
        ]);
    }
    /**
     * @Route("/authorList", name="authorList")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Author')
            ->getAllAuthors();
        $nav = 3;

        return $this->render(':blog:listAuthor.html.twig', [
            'listObj' => $listObj, 'nav' => $nav
        ]);
    }

}