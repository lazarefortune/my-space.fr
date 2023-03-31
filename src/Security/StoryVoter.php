<?php /** @noinspection PhpParameterNameChangedDuringInheritanceInspection */

namespace App\Security;

use App\Entity\Story;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class StoryVoter extends Voter
{
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject) : bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::SHOW, self::EDIT, self::DELETE))) {
            return false;
        }

        // only vote on Story objects inside this voter
        if (!$subject instanceof Story) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $story, TokenInterface $token) : bool
    {
        if (in_array( 'ROLE_ADMIN', $token->getRoleNames() )) {
            return true;
        }

        $user = $token->getUser();


        // you know $subject is a Post object, thanks to supports
        /** @var Story $story */

        switch ($attribute) {
            case self::SHOW:
                if( $story->getPrivacy() == $story::PRIVACY_PUBLIC && !$story->getIsDraft() && !$story->getIsTrash()) {
                    return true;
                }
                if ( !$this->checkUser($user) ) {
                    return false;
                }
                return $this->canView($story, $user);
            case self::EDIT:
                if ( !$this->checkUser($user) ) {
                    return false;
                }
                return $this->canEdit($story, $user);
            case self::DELETE:
                if ( !$this->checkUser($user) ) {
                    return false;
                }

                return $this->canDelete($story, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function checkUser($user) : bool
    {
        return $user instanceof UserInterface;
    }

    private function canView(Story $story, UserInterface $user) : bool
    {
        return $this->canEdit($story, $user);
    }

    private function canEdit(Story $story, UserInterface $user) : bool
    {
        return $this->canDelete($story, $user);
    }

    private function canDelete(Story $story, UserInterface $user) : bool
    {
        return $user === $story->getAuthor();
    }
}