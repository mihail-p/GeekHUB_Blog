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
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Author')
            ->getAllAuthors();

        return $this->render(':blog/Admin:listAuthor.html.twig', ['listObj' => $listObj]);
    }

}