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
        $countTag = $this->countTag($listObj);
        $this->shortPost($listObj);
        $this->shortComment($listComm);
        $sortTS = $this->sortTotalScore($listObj);

        return $this->render(':blog:listPosts.html.twig', ['listObj' => $listObj, 'countTag' => $countTag,
            'listComm' => $listComm, 'sortTotalScore' => $sortTS
        ]);
    }

    /**
     * @Route("/post/tag/{tag}", name="postsWithTag")
     */
    public function postsWithTagAction($tag)
    {
        $listObj = $this->getDoctrine()->getRepository('AppBundle:Post')->getPostsWithTag($tag);
        $countTag = $this->countTag($listObj);
        $this->shortPost($listObj);

        return $this->render(':blog:listPosts.html.twig', ['listObj' => $listObj, 'countTag' => $countTag]);
    }

    /**
     * @Route("/post.show/{slug}", name="showOnePost", requirements={"slug" = "[a-z1-9\-_\/]+"})
     */
    public function showPostAction(Request $request, $slug)
    {
        $comment = new Comment();
        $comment->setDateTime(new \DateTime());


        $form = $this->createForm(new CommentAddType(), $comment);
        $form->add('add comment', SubmitType::class);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findOneBy(['slug' => $slug]);
        $countTag = $this->countTag($post);
        $comment->setPost($post);

        if ($form->isValid()) {
            /* calc and persist totalScore from comments */
            $comments = $post->getComments()->getValues();
            $ammComments = $post->getComments()->count();
            if ($ammComments == 0) { /* div. by zero */
                $ammComments = 1;
            } else {
                $ammComments++;/* because current! comment*/
            }
            $sumScore = 0;
            foreach ($comments as $item) {
                $number = $item->getscore();
                $sumScore = $sumScore + $number;
            }
            $sumScore = $sumScore + $comment->getScore();
            $totalScore = round($sumScore / $ammComments);
            $post->setTotalScore($totalScore);
            /* end total comments */

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
        foreach ($listObj as $post) {
            $item = $post->getPost();
            $string = mb_substr($item, 0, 300, 'UTF-8');
            $post->setPost($string);
        }
        return $listObj;
    }

    private function shortComment($listObj)
    {
        foreach ($listObj as $comm) {
            $item = $comm->getComment();
            if (mb_strlen($item, 'UTF-8') > 30){
                $string = mb_substr($item, 0, 30, 'UTF-8').'...';
                $comm->setComment($string);
            }
        }
        return $listObj;
    }

    private function sortTotalScore( $listObj)
    {
        $arrTitle =[];
        foreach($listObj as $post){
            $slug = $post->getSlug();
            $title = $post->getTitle();
            $arrTitle[$slug] = $title;
            $arr[$slug] = $post->getTotalScore();
        }
        arsort($arr);
        $sortArr = array_slice($arr, 0, 5, true);
        /* replace totalScore to title  */
        foreach ($arrTitle as $key => $value) {
            foreach($sortArr as $sortKey => $sortValue){
                if($sortKey == $key){$sortArr[$sortKey] = $value;}
            }
        }
        return $sortArr;
    }
}
