<?php

namespace App\Events;

use App\Entity\UserOwnGame;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserOwnGamesEventSubscriber implements EventSubscriber
{

    public function getSubscribedEvents(): array
    {
        return [
          Events::preUpdate
        ];
    }

    public function preUpdate(LifecycleEventArgs $lifecycleEventArgs): void {
        $object = $lifecycleEventArgs->getObject();

        if ($object instanceof UserOwnGame) {
            $object->setLastUsedAt(new DateTime());
        }
    }
}
