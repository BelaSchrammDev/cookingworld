<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageHelper
{
    private EntityManagerInterface $entityManager;
    private ParameterBagInterface $parameterBag;
    // Assuming you have Doctrine's EntityManagerInterface injected into this service
    public function __construct(EntityManagerInterface $entityManager, ParameterBagInterface $params)
    {
        $this->entityManager = $entityManager;
        $this->parameterBag = $params;
    }

    public function setDefaultAvatar(User $user): void
    {
        $user->setPicture("");
        $user->setAvatar("0_default.png");
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getUserImagePath($user): string
    {
        $picture = $user->getPicture();
        $avatar = $user->getAvatar();
        if ($picture)
            return $picture;
        else if ($avatar)
            return 'default/' . $avatar;
        else
            return 'default/0_default.png';
    }

}
