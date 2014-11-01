<?php

namespace Sententiaregum\Bundle\UserBundle\Entity\Api;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return string
     */
    public function getApiToken();

    /**
     * @return \DateTime
     */
    public function getRegistrationDate();

    /**
     * @return string
     */
    public function getRealName();

    /**
     * @return \DateTime
     */
    public function getLastAction();

    /**
     * @return boolean
     */
    public function isOnline();
}
