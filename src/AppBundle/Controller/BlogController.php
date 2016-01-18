<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CommentAddType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BlogController extends Controller
{
    /**
     * @Route("/", name="indexBlog")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $nav = 1;
        return $this->render('blog/index.html.twig', ['nav' => $nav
        ]);
    }

    /**
     * @Route("/admin", name="adminBlog")
     */
    public function listAdminAction(Request $request)
    {
        // replace this example code with whatever you need
        $nav = 8;
        return $this->render(':blog/Admin:index.html.twig', ['nav' => $nav
        ]);
    }

    /**
     * @Route("/posts.list", name="postList")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Post')->getPosts();
        $countTag = $this->countTag($listObj);
        $this->shortPost($listObj);
        $nav = 5;

        return $this->render(':blog:listPosts.html.twig', [
            'listObj' => $listObj, 'nav' => $nav, 'countTag' => $countTag
        ]);
    }

    /**
     * @Route("/post/tag/{tag}", name="postsWithTag")
     */
    public function postsWithTag($tag)
    {
        $listObj = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsWithTag($tag);
        $countTag = $this->countTag($listObj);
        $this->shortPost($listObj);
        $nav = 5;

        return $this->render(':blog:listPosts.html.twig', ['listObj' => $listObj, 'nav' => $nav,
        'countTag' => $countTag]);
    }

    /**
     * @Route("/post.show/{slug}", name="showOnePost", requirements={"slug" = "[a-z1-9\-_\/]+"})
     */
    public function showPostAction(Request $request,  $slug)
    {
        $comment = new Comment();
        $comment->setDateTime(new \DateTime());


        $form = $this->createForm(new CommentAddType(), $comment);
        $form->add('add comment', SubmitType::class);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Post')->findBy(['slug' => $slug]);
        $countTag = $this->countTag($listObj);
        $nav = 0;

        if ($form->isValid()) {
            $emen = $this->getDoctrine()->getManager();
            $emen->persist($comment);
            $emen->flush();

            $nav = 11;
            return $this->render(':blog:addItemOk.html.twig', ['nav' => $nav]);
        }

        return $this->render(':blog:listPosts.html.twig', ['listObj' => $listObj, 'nav' => $nav,
            'countTag' => $countTag, 'form' => $form->createView()
        ]);
    }

    private function countTag($listPosts)
    {
        $tags = [];
        $tagEl = [];
        $i = 1;
        foreach ($listPosts as $posts) {
            $tags[$posts->getId()] = $posts->getTags()->getValues();
        }
        foreach ($tags as $tag_name) {
            foreach ($tag_name as $item) {
                $tagEl[$i] = $item->getTag();
                $i++;
            }
        }
        $countTag = array_count_values($tagEl);
        return $countTag;
    }

    private function shortPost($listObj)
    {
        foreach($listObj as $post){
            $item = $post->getPost();
            $string = mb_substr($item, 0, 300, 'UTF-8');
            $post->setPost($string);
        }
        return $listObj;
    }
}
