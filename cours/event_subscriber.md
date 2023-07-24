### Etapes pour l'EventSubscriber

- Créer une simple classe PHP qui implémente "EventSubscriber"
- Il faut ensuite qu'elle implémente correctement l'interface "EventSubscriber" en implémentant la méthode "getSubscribedEvents", dans laquelle on lui indique le nom de l'évènement traité par cet EventSubscriber

```php
public function getSubscribedEvents(): array
{
    return [
        Events::prePersist,
    ];
}
```

(Il faut que la classe possède une méthode du même nom que l'évènement)

- Afin que l'EventSubscriber soit pris en compte il faut l'ajouter dans la liste du "config/services.yaml" :

```yaml
App\Events\PrePersistEventSubscriber:
    tags:
        - { name: doctrine.event_listener, event: prePersist } 
```

Ce code se place au même niveau que le
```yaml
App\Controller\:
```

Et il faut bien indiquer l'event traité à l'intérieur de l'EventSubscriber.

PS : un EventSubscriber par défaut ne traite jamais une seule entité, mais toute les entités qui passeront par cet Event, il faut donc bien pensé à le traiter au cas par cas par un "instance of" :

```php
if ($object instanceof Country) {
    $object->setUrlFlag('https://flagcdn.com/32x24/'.$object->getCode().'.png');
}
```
