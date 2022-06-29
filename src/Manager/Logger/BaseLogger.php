<?php

declare(strict_types=1);

namespace App\Manager\Logger;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class BaseLogger.
 */
class BaseLogger
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @var UserInterface|null
     */
    protected ?UserInterface $user;

    /**
     * LoggerManager constructor.
     *
     * @param LoggerInterface     $logger
     * @param SerializerInterface $serializer
     * @param Security            $security
     */
    public function __construct(LoggerInterface $logger, SerializerInterface $serializer, Security $security)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->user = $security->getUser();
    }
}
