<?php

namespace OC\PlatformBundle\Listener;

use OC\PlatformBundle\Event\WelcomeEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class WelcomeListener
{
    /**
     * @var WelcomeEvent
     */
    private $welcome;

    public function __construct(WelcomeEvent $welcome)
    {
        $this->welcome = $welcome;
    }

    public function processWelcome(FilterResponseEvent $event)
    {
        $response = $this->welcome->addWelcome($event->getResponse());

        $event->setResponse($response);
    }
}
