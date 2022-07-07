<?php

namespace App\Service;

use App\Entity\User;
use App\Mapper\UserEditMapper;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Request\EditRoleRequest;
use App\Request\UserRequest;
use App\Transformer\OrderTransformer;
use App\Transformer\UserTransformer;
use App\Repository\ImageRepository;
use Symfony\Component\Security\Core\Security;

class UserService
{
    private UserRepository $userRepository;
    private UserTransformer $userTransformer;
    private UserEditMapper $userEditMapper;
    private OrderTransformer $orderTransformer;
    private ReviewRepository $reviewRepository;

    public function __construct(
        UserRepository   $userRepository,
        UserTransformer  $userTransformer,
        UserEditMapper   $userEditMapper,
        ReviewRepository $reviewRepository,
        Security         $security,
        OrderTransformer $orderTransformer
    )
    {
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->userEditMapper = $userEditMapper;
        $this->reviewRepository = $reviewRepository;
        $this->security = $security;
        $this->orderTransformer = $orderTransformer;
    }

    public function getAllOrder(): array
    {
        $currentUser = $this->security->getUser();
        $result = [];
        $result['user']['id'] = $currentUser->getId();
        $result['user']['email'] = $currentUser->getEmail();
        $result['user']['fullname'] = $currentUser->getName();
        $result['user']['avatar'] = $currentUser->getAvatar();
        foreach ($currentUser->getOrders() as $key => $order) {
            $result['orders'][$key] = $this->orderTransformer->detailToArray($order);
        }
        return $result;
    }

    public function getUsers(UserRequest $userRequest): array
    {
        $userRole = ["ROLE_USER"];
        $data = $this->userRepository->getAll($userRequest);
        $users = $data['users'];
        $results = [];
        $count = 0;
        foreach ($users as $user) {
            if ($user->getRoles() === $userRole) {
                $results['users'][] = $this->userTransformer->fromArray($user);
                $count += 1;
            }
        }
        $results['totalPages'] = $data['totalPages'];
        $results['page'] = $data['page'];
        $results['totalUsers'] = $count;

        return $results;
    }

    public function editRole(User $user, EditRoleRequest $editRoleRequest): array
    {
        $editUserMapper = $this->userEditMapper->mapping($user, $editRoleRequest);
        $this->userRepository->add($editUserMapper, true);
        $result = $this->userTransformer->fromArray($user);

        return $result;
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
