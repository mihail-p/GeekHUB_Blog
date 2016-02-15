<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\CommentAddType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class PostController
 * @Route("/{_locale}/admin/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route("/edit/{id}", name="commentEdit")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commentObj = $em->getRepository('AppBundle:Comment')->find($id);
        $commentObj->setDateTime(new \DateTime());

        $form = $this->createForm(CommentAddType::class, $commentObj);
        $form->add('edit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isValid()){
            $em->flush();

            return $this->redirectToRoute('showOnePost', ['slug' => $commentObj->getPost()->getSlug()]);
        }

        return $this->render(':blog/Admin:editComment.html.twig', ['form' => $form->createView()]);

    }
}
