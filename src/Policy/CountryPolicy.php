<?php

declare(strict_types=1);

namespace DhcrCore\Policy;

use Authorization\IdentityInterface;
use DhcrCore\Model\Entity\Country;

class CountryPolicy
{
    public function canAdd(IdentityInterface $user, Country $country)
    {
        if ($user->is_admin) {
            return true;
        }
        return false;
    }

    public function canEdit(IdentityInterface $user, Country $country)
    {
        if ($user->is_admin) {
            return true;
        }
        return false;
    }
}
