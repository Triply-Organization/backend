<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\BaseRequest;
use App\Mapper\RegisterMapper;
use PHPMailer\PHPMailer\Exception;

class RegisterService
{
    private UserRepository $userRepository;
    private RegisterMapper $registerMapper;
    private SendMailService $sendMailService;

    public function __construct(
        UserRepository $userRepository,
        RegisterMapper $registerMapper,
        SendMailService $sendMailService
    ) {
        $this->userRepository = $userRepository;
        $this->registerMapper = $registerMapper;
        $this->sendMailService = $sendMailService;
    }

    /**
     * @throws Exception
     */
    public function register(BaseRequest $requestData): User
    {
        $user = $this->registerMapper->mapping($requestData);
        $emailSubject = 'Welcome';
        $this->userRepository->add($user, true);
        $this->sendMailService->sendSimpleMail($user->getEmail(), $emailSubject);

        return $user;
    }
}
