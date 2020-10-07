<?php

namespace App\EventListener;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UpdateLastLoginListener implements AuthenticationSuccessHandlerInterface
{
    private $em;
    private $route;

    public function __construct(EntityManagerInterface $em, RouterInterface $r)
    {
        $this->em = $em;
        $this->route = $r;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $logEvent)
    {
        $token = $logEvent->getAuthenticationToken();
        $requset = $logEvent->getRequest();
        $this->onAuthenticationSuccess($requset, $token);
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $token->getUser();
        $user->setLastLogin(new DateTime());

        $this->em->persist($user);
        $this->em->flush();

        return new RedirectResponse(
            $request->getSession()->get('_security.main.target_path') ?? $this->route->generate('index')
        );
    }
}
