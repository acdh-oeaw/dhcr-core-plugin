<?php
declare(strict_types=1);

namespace DhcrCore\Policy;

use Authorization\IdentityInterface;
use DhcrCore\Model\Entity\Course;

/**
 * Course policy
 */
class CoursePolicy
{
    /**
     * Check if $user can add Course
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \DhcrCore\Model\Entity\Course $course
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Course $course)
    {
        return true;
    }

    /**
     * Check if $user can edit Course
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \DhcrCore\Model\Entity\Course $course
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Course $course)
    {
    }

    /**
     * Check if $user can delete Course
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \DhcrCore\Model\Entity\Course $course
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Course $course)
    {
    }

    /**
     * Check if $user can view Course
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \DhcrCore\Model\Entity\Course $course
     * @return bool
     */
    public function canView(IdentityInterface $user, Course $course)
    {
    }
}
