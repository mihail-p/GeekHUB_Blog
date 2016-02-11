<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/10/16
 * Time: 10:41 AM
 */

namespace AppBundle\Controller\Admin;

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
     * @Route("/login1", name="loginUser")
     */
    public function loginAction(Request $request)
    {
        /*  $author = new Author();
          $form = $this->createFormBuilder($author)
              ->setAction($this->generateUrl('loginUser'))
              ->setMethod('Post')
              ->add('Username', TextType::class)
              ->add('Password', PasswordType::class)
              ->add('Login', SubmitType::class)
              ->getForm();
       */
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(':blog:loginForm.html.twig', [
           /* 'form' => $form->createView(), */
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/authorAdd", name="authorAdd")
     */
    public function addAction(Request $request)
    {
        $author = new Author();
        $author->setUsername('Author1');
        $author->setDateTime(new \DateTime());

        $form = $this->createFormBuilder($author)
            ->add('Username', TextType::class)
            ->add('Password', PasswordType::class)
           // ->add('DateTime', DateTimeType::class)
            ->add('Add', SubmitType::class)
            ->getForm();
        $msg = 'Add author';
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            $msg = 'author added';
            return $this->render(':blog/Admin:addItemOk.html.twig', ['msg' => $msg]);
        }

        return $this->render(':blog/Admin:addItem.html.twig', [
            'form' => $form->createView(), 'msg' => $msg
        ]);
    }
    /**
     * @Route("/authorList", name="authorList")
     */
    public function listAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Author')
            ->getAllAuthors();

        return $this->render(':blog/Admin:listAuthor.html.twig', ['listObj' => $listObj]);
    }

}