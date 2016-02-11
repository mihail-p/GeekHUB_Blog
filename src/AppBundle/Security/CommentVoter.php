<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/11/16
 * Time: 4:48 PM
 */

namespace AppBundle\Security;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Author;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    const EDIT = 'edit';
    const REMOVE = 'remove';
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::REMOVE, self::EDIT))) {
            return false;
        }
        // only vote on Post objects inside this voter
        if (!$subject instanceof Comment) {
            return false;
        }
        return true;
    }
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof Author) {
            // the user must be logged in; if not, deny access
            return false;
        }
        // you know $subject is a Post object, thanks to supports
        /** @var Comment $comment */
        $comment = $subject;
        switch ($attribute) {
            case self::REMOVE:
                return $this->canRemove($comment, $user);
            case self::EDIT:
                return $this->canEdit($comment, $user);
        }
        throw new \LogicException('This code should not be reached!');
    }
    private function canRemove(Comment $comment, Author $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($comment, $user)) {
            return true;
        }
        return false;
    }
    private function canEdit(Comment $comment, Author $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object

        return ($user === $comment->getAuthor()
            or $user === $comment->getPost()->getAuthor()
        );
    }
}