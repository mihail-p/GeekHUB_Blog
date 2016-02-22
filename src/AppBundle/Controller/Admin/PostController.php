<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/10/16
 * Time: 10:41 AM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\FileUpload;
use AppBundle\Entity\Post;
use AppBundle\Form\PostAddType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PostController
 * @Route("/{_locale}/admin/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/add", name="postAdd")
     */
    public function addAction(Request $request)
    {
        if (!($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') or
            $this->get('security.authorization_checker')->isGranted('ROLE_MODERATOR'))
        ) {
            throw $this->createAccessDeniedException();
        }
        $post = new Post();
        $post->setTitle('Title name');
        $post->setDateTime(new \DateTime());
        $post->setAuthor($this->getUser());
        $post->setTotalScore(0);

        $form = $this->createForm(PostAddType::class, $post);
        $form->add('add post', SubmitType::class);

        $form->handleRequest($request);
        $msg = 'Add post';
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $msg = 'post added';

            return $this->render(':blog/Admin:addItemOk.html.twig', ['msg' => $msg]);
        }

        return $this->render(':blog/Admin:addItem.html.twig', [
            'form' => $form->createView(), 'msg' => $msg
        ]);
    }

    /**
     * @Route("/list", name="admPostList")
     */
    public function listAction()
    {
        if (!($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') or
            $this->get('security.authorization_checker')->isGranted('ROLE_MODERATOR'))
        ) {
            throw $this->createAccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Post')->getPosts();

        $deleteForms = [];
        foreach ($listObj as $entity) {
            $deleteForms[$entity->getId()] = $this->createFormBuilder($entity)
                ->setAction($this->generateUrl('admPostDel', array('id' => $entity->getId())))
                ->setMethod('DELETE')
                ->add('submit', SubmitType::class, ['label' => ' ', 'attr' => ['class' => 'glyphicon glyphicon-trash btn-link']])
                ->getForm()->createView();
        }
        return $this->render(':blog/Admin:admListPosts.html.twig', [
            'listObj' => $listObj, 'delForms' => $deleteForms
        ]);
    }

    /**
     * @Route("/mod/{id}", name="admPostMod",
     *     requirements={"id": "\d+"})
     */
    public function modAction($id, Request $request)
    {
        $pict = new FileUpload();
        $em = $this->getDoctrine()->getManager();
        $postObj = $em->getRepository('AppBundle:Post')->find($id);
        if (!($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') or
            $this->get('security.authorization_checker')->isGranted('edit', $postObj))
        ) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(PostAddType::class, $postObj);
        $form->add('modify', SubmitType::class);

        /*$pict->setPost($postObj);*/
        $formUpload = $this->createFormBuilder($pict)
            ->add('file', FileType::class)
            ->add('Upload', SubmitType::class)
            ->getForm();
        $msg = 'Edit post';
        $uploads = 0; $var1 = 0; $var2 = 0;

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            $formUpload->handleRequest($request);

            if ($form->isValid()) {

                $em->flush();
                $msg = 'post "' . $postObj->getTitle() . '" was modified';

                /*return $this->redirectToRoute('admPostList', ['msg' => $msg]); */
            }
            if ($formUpload->isValid()) {

                $uploads = $formUpload['file']->getData();
                /*$uploads = $pict;*/
                $var1 = $pict->getPath();
                /*$postObj->setPictPath('Controller');*/
                $pict->setPost($postObj);
                $pict->setOrigName($uploads->getClientOriginalName());
                /*$pict->setOrigName($pict->getPath());*/

                $upFiles = $em->getRepository('AppBundle:FileUpload')->findAll();
                foreach ($upFiles as $item) {
                    $uplPost = $item->getPost();
                    if ($uplPost == null) {
                        continue;
                    }
                    $upId = $uplPost->getId();
                    if ($upId == $id) {
                        $em->remove($item);
                    }
                }
                /*$uploads->move('./media/', $uploads->getClientOriginalName());*/
                $em->persist($pict);
                $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
                $uploadableManager->markEntityToUpload($pict, $pict->getFile());
                $postObj->setPictPath('Controller 222');
                /*$var2 = $pict->getPath();*/
                /*$var2 = $postObj->getPictPath();*/
                $var2 = $em;
                $em->flush();


                $msg = 'post "' . $postObj->getTitle() . '" was modified';

                /*return $this->redirectToRoute('admPostList', ['msg' => $msg]); */
            }
        }
        return $this->render(':blog/Admin:addItem.html.twig',
            ['form' => $form->createView(), 'msg' => $msg, 'uploads' => $uploads, 'var1' =>$var1, 'var2' =>$var2,
                'formUpload' => $formUpload->createView()]);
    }

    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}", name="admPostDel")
     * @Method("DELETE")
     */
    public function delAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Post')->find($id);
        $em->remove($entity);
        $em->flush();

        return $this->redirectToRoute('admPostList');
    }
}