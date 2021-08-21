<?php


namespace App\EventSubscribers;


use App\Entity\Product;
use App\Entity\User;
use App\Model\ModifiedUserInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class ModifiedUserSubscriber implements EventSubscriber
{

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;


    /**
     * ModifiedUserSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }


    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        if ($this->tokenStorage->getToken() != null) {
            $user = $this->tokenStorage->getToken()->getUser();
            $entity = $args->getObject();
            if ($entity instanceof ModifiedUserInterface) {
                $entity->setCreatedUser($user->getUsername());
                $entity->setUpdatedUser($user->getUsername());
            }
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {

        if ($args->getEntity() instanceof Product &&
            !$args->hasChangedField('views')
            && $args->getEntity() instanceof ModifiedUserInterface
        ) {
            if ($this->tokenStorage->getToken() != null) {
                $user = $this->tokenStorage->getToken()->getUser();
                $entity = $args->getObject();

                $entity->setUpdatedUser($user->getUsername());

            }
        }


    }
}