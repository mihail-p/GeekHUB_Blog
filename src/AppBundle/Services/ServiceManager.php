<?php

namespace AppBundle\Services;


class ServiceManager
{
    private $eny;

    /**
     * @param $value
     */
    public function setEny($value)
    {
        $this->eny = $value;
    }

    /**
     * @return mixed
     */
    public function getEny()
    {
        return $this->eny;
    }

    public function countTag($listPosts)
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

    public function shortPost($listObj)
    {
        foreach ($listObj as $post) {
            $item = $post->getPost();
            $string = mb_substr($item, 0, 300, 'UTF-8');
            $post->setPost($string);
        }
        return $listObj;
    }

    public function shortComment($listObj)
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

    public function sortPopularPosts( $listObj)
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

    public function calcTotalScore($post, $comment)
    {
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
    }
}