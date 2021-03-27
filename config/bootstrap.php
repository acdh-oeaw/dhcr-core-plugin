<?php
// this file needs to be present for performing tests from app level
// using the app-level phpunit.xml.dist

use Cake\Core\Configure;
Configure::write('dhcr.expirationPeriod', 60*60*24*489);

