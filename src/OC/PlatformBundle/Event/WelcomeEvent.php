<?php

namespace OC\PlatformBundle\Event;

use Symfony\Component\HttpFoundation\Response;

class WelcomeEvent
{
    public function addWelcome(Response $response)
    {
        $content = $response->getContent();

        $script = '
        <script>
            alert("Bienvenue sur le site");
        </script>';

        $content = str_replace('</body>', '</body>'.$script, $content);

        $response->setContent($content);

        return $response;
    }
}
