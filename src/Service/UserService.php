<?php

namespace App\Service;

use App\Entity\User;
use App\Mapper\UserUpdateMapper;
use App\Repository\OrderRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Request\PatchUpdateUserRequest;
use App\Request\UserGetAllOrderRequest;
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
    private OrderRepository $orderRepository;
    private ParameterBagInterface $params;

    public function __construct(
        UserRepository $userRepository,
        UserTransformer $userTransformer,
        UserUpdateMapper $userUpdateMapper,
        ReviewRepository $reviewRepository,
        OrderRepository $orderRepository,
        OrderTransformer $orderTransformer,
        ParameterBagInterface $params
    ) {
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->userUpdateMapper = $userUpdateMapper;
        $this->reviewRepository = $reviewRepository;
        $this->orderTransformer = $orderTransformer;
        $this->orderRepository = $orderRepository;
        $this->params = $params;
    }

    public function getAllOrder(UserGetAllOrderRequest $userGetAllOrderRequest, $currentUser): array
    {
        $results = [];
        $results['user']['id'] = $currentUser->getId();
        $results['user']['email'] = $currentUser->getEmail();
        $results['user']['fullname'] = $currentUser->getName();
        $results['user']['avatar'] = $currentUser->getAvatar()
            ? $this->params->get('s3url') . $currentUser->getAvatar()->getPath()
            : null;
        $data = $this->orderRepository->getAllOrder($userGetAllOrderRequest, $currentUser);
        foreach ($data['orders'] as $key => $order) {
            if ($this->orderTransformer->getOrderOfUser($order) === null) {
                continue;
            }
            $results['orders'][$key] = $this->orderTransformer->getOrderOfUser($order);
        }
        $results['totalPages'] = $data['totalPages'];
        $results['page'] = $data['page'];
        $results['totalOrders'] = $data['totalOrders'];

        return $results;
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

    public function update(User $user, PatchUpdateUserRequest $patchUpdateUserRequest)
    {
        $user = $this->userUpdateMapper->mapping($user, $patchUpdateUserRequest);
        $this->userRepository->add($user, true);
        return true;
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
