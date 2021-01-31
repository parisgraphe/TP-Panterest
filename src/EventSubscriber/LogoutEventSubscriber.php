<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;

class LogoutEventSubscriber implements EventSubscriberInterface
{
	private $urlGenerator;
	private $flashBag;
	private $security;

	public function __construct(Security $security, FlashBagInterface $flashBag, UrlGeneratorInterface $urlGenerator)
	{
		$this->urlGenerator = $urlGenerator;
		$this->flashBag = $flashBag;
		$this->security = $security;
	}

    public function onLogoutEvent(LogoutEvent $event)
    {
        $this->flashBag->add('success', 'Bye bye ' . $this->security->getUser()->getFullName());
		$event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
