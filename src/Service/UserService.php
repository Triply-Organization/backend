<?php

namespace App\Service;

use App\Entity\User;
use App\Mapper\UserUpdateMapper;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Request\PatchUpdateUserRequest;
use App\Request\UserRequest;
use App\Transformer\OrderTransformer;
use App\Transformer\UserTransformer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserTransformer $userTransformer;
    private UserUpdateMapper $userUpdateMapper;
    private OrderTransformer $orderTransformer;
    private ReviewRepository $reviewRepository;
    private ParameterBagInterface $params;

    public function __construct(
        UserRepository $userRepository,
        UserTransformer $userTransformer,
        UserUpdateMapper $userUpdateMapper,
        ReviewRepository $reviewRepository,
        OrderTransformer $orderTransformer,
        ParameterBagInterface $params
    ) {
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->userUpdateMapper = $userUpdateMapper;
        $this->reviewRepository = $reviewRepository;
        $this->orderTransformer = $orderTransformer;
        $this->params = $params;
    }

    public function getAllOrder($currentUser): array
    {
        $result = [];
        $result['user']['id'] = $currentUser->getId();
        $result['user']['email'] = $currentUser->getEmail();
        $result['user']['fullname'] = $currentUser->getName();
        $result['user']['avatar'] = $currentUser->getAvatar()
            ? $this->params->get('s3url') . $currentUser->getAvatar()->getPath()
            : null;
        foreach ($currentUser->getOrders() as $key => $order) {
            $result['orders'][$key] = $this->orderTransformer->getOrderOfUser($order);
        }
        return $result;
    }

    public function getUsers(UserRequest $userRequest): array
    {
        $userRole = '["ROLE_USER"]';
        $data = $this->userRepository->getAll($userRequest, $userRole);
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

    public function update(User $user, PatchUpdateUserRequest $patchUpdateUserRequest): void
    {
        $user = $this->userUpdateMapper->mapping($user, $patchUpdateUserRequest);
        $this->userRepository->add($user, true);
    }

    public function deleteUser(User $user): bool
    {
        $this->reviewRepository->deleteWithRelation('user', $user->getId());
        $this->userRepository->delete($user->getId());

        return true;
    }

    public function undoDeleteUser(User $user): bool
    {
        $this->reviewRepository->undoDeleteWithRelation('user', $user->getId());
        $this->userRepository->undoDelete($user->getId());

        return true;
    }
}
