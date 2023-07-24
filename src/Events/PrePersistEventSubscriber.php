<?php

namespace App\Events;

use App\Entity\Country;
use App\Entity\SlugInterface;
use App\Service\TextService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PrePersistEventSubscriber implements EventSubscriber
{

    public function __construct(private TextService $textService)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void {
        $object = $eventArgs->getObject();

        if ($object instanceof SlugInterface) {
            $object->setSlug($this->textService->slugify($object->getName()));
        }

        if ($object instanceof Country) {
            $object->setUrlFlag('https://flagcdn.com/32x24/'.$object->getCode().'.png');
        }
    }

}
