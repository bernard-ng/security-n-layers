<?php

declare(strict_types=1);

namespace App\Notification;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class Email
 * @package App\Notification
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EmailFactory
{

    private Environment $twig;
    private LoggerInterface $logger;

    /**
     * Email constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig, LoggerInterface $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * @param string $template
     * @param array $data
     * @return Email
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function makeFromTemplate(string $template, array $data = []): Email
    {
        try {
            return (new Email())
                ->from(new Address('noreply@souvenircloud.com', 'SouvenirCloud'))
                ->html($this->twig->render($template, array_merge($data, ['format' => 'html'])))
                ->text($this->twig->render($template, array_merge($data, ['format' => 'text'])));
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
