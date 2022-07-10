<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use App\Request\UserRequest;
use App\Transformer\UserTransformer;

class CustomerService
{
    private UserRepository $userRepository;
    private UserTransformer $userTransformer;
    private TourRepository $tourRepository;

    public function __construct(
        UserRepository $userRepository,
        UserTransformer $userTransformer,
        TourRepository $tourRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->tourRepository = $tourRepository;
    }

    public function getCustomers(UserRequest $userRequest): array
    {
        $customerRole = '["ROLE_CUSTOMER"]';
        $data = $this->userRepository->getAll($userRequest, $customerRole);
        $users = $data['users'];
        $results = [];
        foreach ($users as $user) {
            $results['customers'][] = $this->userTransformer->fromArray($user);
        }
        $results['totalPages'] = $data['totalPages'];
        $results['page'] = $data['page'];
        $results['totalCustomers'] = $data['totalUsers'];

        return $results;
    }

    public function deleteCustomer(User $user): bool
    {
        $this->tourRepository->deleteWithRelation('createdUser', $user->getId());
        $this->userRepository->delete($user->getId());

        return true;
    }

    public function undoDeleteCustomer(User $user): bool
    {
        $this->tourRepository->undoDeleteWithRelation('createdUser', $user->getId());
        $this->userRepository->undoDelete($user->getId());

        return true;
    }
}
