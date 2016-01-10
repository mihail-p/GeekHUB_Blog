<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/10/16
 * Time: 10:41 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Authors;
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
        $author = new Authors();
        $author->setAuthor('Author1');
        $author->setDateTime(new \DateTime());

        $form = $this->createFormBuilder($author)
            ->add('Author', TextType::class)
            ->add('Passw', PasswordType::class)
            ->add('DateTime', DateTimeType::class)
            ->add('Add', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            //$formString = $form->getData()->getTeam()->getCountry();
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirect($this->generateUrl('authorAddOk'));
        }

        return $this->render(':blog:addAuthor.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/authorAddOk", name="authorAddOk")
     */
    public function addOk()
    {
        return $this->render(':blog:addAuthorOk.html.twig');
    }

}