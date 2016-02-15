<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/10/16
 * Time: 10:41 AM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use AppBundle\Form\PostAdd;
use AppBundle\Form\PostAddType;
use AppBundle\Form\TagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TagController
 * @package AppBundle\Controller\Admin
 * @Route("/{_locale}/admin/tag")
 */
class TagController extends Controller
{
    /**
     * @Route("/add", name="tagAdd")
     */
    public function addAction(Request $request)
    {
        $tag = new Tag();
        $tag->setTag('name of tag here');

        $form = $this->createForm(TagType::class, $tag);
        $form->add('add tag', SubmitType::class);

        $form->handleRequest($request);
        $msg = 'Add tag';
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();
            $msg = 'tag added';

            return $this->render(':blog/Admin:addItemOk.html.twig', ['msg' => $msg]);
        }

        return $this->render(':blog/Admin:addItem.html.twig', [
            'form' => $form->createView(), 'msg' => $msg
        ]);
    }
    /**
     * @Route("/list", name="tagList")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Tag')
            ->findAll();

        return $this->render(':blog/Admin:listTags.html.twig', [
            'listObj' => $listObj
        ]);
    }
}
