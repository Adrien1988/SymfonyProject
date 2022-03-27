<?php

namespace App\Notification;

use Swift_Message;
use App\Entity\User;
use Twig\Environment;

class RegisterNotification
{
  /**
   * @var \Swift_Mailer
   */
  private $mailer;

  /**
   * @var Environment
   */
  private $renderer;

  public function __construct(\Swift_Mailer $mailer, Environment $renderer)
  {
    $this->mailer = $mailer;

    $this->renderer = $renderer;
  }

  public function notify(User $user)
  {
    $message = (new Swift_Message('eArchery - Nouvelle inscription'))
      ->setFrom('no-reply@eArchery.fr')
      ->setTo($user->getEmail())
      ->setBody(
        $this->renderer->render('emails/register.html.twig', ['user' => $user ]),
        'text/html'
      );

    $this->mailer->send($message);
  }
}
