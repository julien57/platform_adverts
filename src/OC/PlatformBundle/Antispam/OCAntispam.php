<?php

namespace OC\PlatformBundle\Antispam;

class OCAntispam
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var int
     */
    private $minLength;

    public function __construct(\Swift_Mailer $mailer, int $minLength)
    {
        $this->mailer = $mailer;
        $this->minLength = $minLength;
    }

    public function isSpam(string $text)
    {
        return strlen($text) < $this->minLength;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }
}
