<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Hackaton;
use App\Entity\User;
use App\Entity\Event;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

final class EventMailSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        $eventHackaton = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$eventHackaton instanceof Event || Request::METHOD_POST !== $method) {
            return;
        }

        $hackaton = $eventHackaton->getHackaton();
        $owner = $hackaton->getOwner();
        $participants = $hackaton->getParticipants();
        foreach ($participants as $participant) {
            $email = (new TemplatedEmail())
                ->from('fakemail@mail.com')
                ->to($participant->getEmail())
                ->subject('New event in your hackaton')
                ->htmlTemplate('emails/new_event.html.twig')
                ->context([
                    'owner' => $owner,
                    'hackaton' => $hackaton,
                    'event' => $eventHackaton,
                ]);
            try {
                $this->mailer->send($email);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
