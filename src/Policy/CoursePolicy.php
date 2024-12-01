<?php

declare(strict_types=1);

namespace DhcrCore\Policy;

use Authorization\IdentityInterface;
use DhcrCore\Model\Entity\Course;

class CoursePolicy
{
    public function canAdd(IdentityInterface $user, Course $course)
    {
        return true;
    }

    public function canEdit(IdentityInterface $user, Course $course)
    {
        if ($course->user_id == $user->id) {  // contributor edits own course
            return true;
        }
        if ($user->user_role_id == 2 && $course->country_id == $user->country_id) {  //  moderator edits in own country
            return true;
        }
        if ($user->is_admin) {  // admin can edit all
            return true;
        }
        return false;
    }

    public function canDelete(IdentityInterface $user, Course $course)
    {
        return false;
    }

    public function canView(IdentityInterface $user, Course $course)
    {
        return true;
    }

    public function canApprove(IdentityInterface $user, Course $course)
    {
        if ($user->user_role_id == 2 && $course->country_id == $user->country_id) {  //  moderator approves in own country
            return true;
        }
        if ($user->is_admin) {  // admin can approve all
            return true;
        }
        return false;
    }

    public function canTransfer(IdentityInterface $user, Course $course)
    {
        if ($user->user_role_id == 2 && $course->country_id == $user->country_id) {  //  moderator transfers in own country
            return true;
        }
        if ($user->is_admin) {  // admin can transfer all
            return true;
        }
        return false;
    }
}
