<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
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
        return $this->render('blog/index.html.twig');
    }

    /**
     * @Route("/admin", name="adminBlog")
     */
    public function listAdminAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render(':blog/Admin:index.html.twig');
    }

    /**
     * @Route("/posts.list", name="postsList")
     */
    public function listPostsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Post')->getPosts();
        $listComm = $em->getRepository('AppBundle:Comment')->getLastComments(5);

        $countTag = $this->get('app.manager')->countTag($listObj);
        $this->get('app.manager')->shortPost($listObj);
        $this->get('app.manager')->shortComment($listComm);
        $PopularPosts = $this->get('app.manager')->sortPopularPosts($listObj);

        return $this->render(':blog:listPosts.html.twig', ['listObj' => $listObj, 'countTag' => $countTag,
            'listComm' => $listComm, 'PopularPosts' => $PopularPosts
        ]);
    }

    /**
     * @Route("/post/tag/{tag}", name="postsWithTag")
     */
    public function postsWithTagAction($tag)
    {
        $listObj = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsWithTag($tag);
        $countTag = $this->get('app.manager')->countTag($listObj);;
        $this->get('app.manager')->shortPost($listObj);

        return $this->render(':blog:listPosts.html.twig', ['listObj' => $listObj, 'countTag' => $countTag]);
    }

    /**
     * @Route("/post.show/{slug}", name="showOnePost", requirements={"slug" = "[a-z1-9\-_\/]+"})
     */
    public function showPostAction(Request $request, $slug)
    {
        $comment = new Comment();
        $comment->setDateTime(new \DateTime());

        $comment->setAuthor($this->getUser());

        $form = $this->createForm(CommentAddType::class, $comment);
        $form->add('add comment', SubmitType::class);

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $form->handleRequest($request);
        }

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findOneBy(['slug' => $slug]);
        $countTag = $this->get('app.manager')->countTag($post);
        $comment->setPost($post);

        if ($form->isValid()) {
            $this->get('app.manager')->calcTotalScore($post, $comment);

            $em->persist($comment);
            $em->flush();

            return $this->render(':blog:addCommentOk.html.twig', ['comment' => $comment]);
        }

        return $this->render(':blog:listOnePost.html.twig', ['post' => $post, 'countTag' => $countTag,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $query = $request->get('query');
        $em = $this->getDoctrine()->getManager();
        $listObj = $em->getRepository('AppBundle:Post')->search($query);

        return $this->render(':blog:listPosts.html.twig', ['listObj' => $listObj, 'query' => $query]);

    }
}
