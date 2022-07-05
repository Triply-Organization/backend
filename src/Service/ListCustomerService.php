<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\ListCustomerRequest;
use App\Transformer\UserTransformer;

class ListCustomerService
{
    private UserRepository $userRepository;
    private UserTransformer $userTransformer;
    private ListCustomerRequest $listCustomerRequest;

    public function __construct(
        UserRepository      $userRepository,
        ListCustomerRequest $listCustomerRequest,
        UserTransformer     $userTransformer
    )
    {
        $this->userRepository = $userRepository;
        $this->listCustomerRequest = $listCustomerRequest;
        $this->userTransformer = $userTransformer;
    }

    public function getCustomers(ListCustomerRequest $listCustomerRequest)
    {
        $data = $this->userRepository->getAll($listCustomerRequest);
        $users = $data['users'];
        $results = [];
        foreach ($users as $user) {
            $results['users'][] = $this->userTransformer->fromArray($user);
        }
        $results['totalPages'] = $data['totalPages'];
        $results['page'] = $data['page'];
        $results['totalUsers'] = $data['totalUsers'];

        return $results;
    }
}
