<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\AuthorType;
use AppBundle\Entity\Author;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RegistrationController extends Controller
{
    /**
     * @Route("/{_locale}/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new Author();
        $user->setRole('ROLE_USER');
        $user->setDateTime(new \DateTime());
        $form = $this->createForm(AuthorType::class, $user)
            ->add('Register!', SubmitType::class);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $userName = $form->getData(Author::class)->getUsername();
            $this->get('app.manager')->userExist($userName);
            $em->persist($user);
            $em->flush();

            return $this->render(':blog/Admin:registerOk.html.twig', ['userName' => $userName]);
        }

        return $this->render(
            ':blog/Admin:register.html.twig',
            array('form' => $form->createView())
        );
    }

}