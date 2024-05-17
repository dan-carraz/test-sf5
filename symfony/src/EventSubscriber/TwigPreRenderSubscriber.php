<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Repository\AddressRepository;
use App\Twig\Components\AddStuffToDoctrineInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\UX\TwigComponent\Event\PreRenderEvent;

class TwigPreRenderSubscriber implements EventSubscriberInterface
{
    public function __construct(private AddressRepository $addressRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PreRenderEvent::class => 'onPreRender',
        ];
    }

    public function onPreRender(PreRenderEvent $event): void
    {
        $component = $event->getComponent(); // the component object
        if ($component instanceof AddStuffToDoctrineInterface) {
            $component->setResult($this->addressRepository->getResult());
        }
    }
}
