<?php

namespace OC\PlatformBundle\Listener;

use OC\PlatformBundle\Event\BetaHTMLAdder;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BetaListener
{
    /**
     * @var BetaHTMLAdder
     */
    private $betaHTML;

    /**
     * @var \DateTime
     */
    private $endDate;

    public function __construct(BetaHTMLAdder $betaHTML, $endDate)
    {
        $this->betaHTML = $betaHTML;
        $this->endDate = new \DateTime($endDate);
    }

    public function processBeta(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $remainingDays = $this->endDate->diff(new \DateTime())->days;

        if ($remainingDays <= 0) {
            return;
        }

        $response = $this->betaHTML->addBeta($event->getResponse(), $remainingDays);

        $event->setResponse($response);
    }
}
