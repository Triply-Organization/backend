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
    private ReviewRepository $reviewRepository;
    private OrderTransformer $orderTransformer;

    public function __construct(
        UserRepository $userRepository,
        UserTransformer $userTransformer,
        UserEditMapper $userEditMapper,
        ReviewRepository $reviewRepository,
        Security $security,
        OrderTransformer $orderTransformer
    ) {
        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->userEditMapper = $userEditMapper;
        $this->reviewRepository = $reviewRepository;
        $this->security = $security;
        $this->orderTransformer = $orderTransformer;
    }

    public function getAllOrder()
    {
        $currentUser = $this->security->getUser();
        $result = [];
        $result['user']['id'] = $currentUser->getId();
        $result['user']['email'] = $currentUser->getEmail();
        $result['user']['fullname'] = $currentUser->getName();
        $result['user']['avatar'] = $currentUser->getAvatar();
        foreach ($currentUser->getOrders() as $key => $order) {
            $result['orders'][$key] = $this->orderTransformer->getTourOfUser($order);
        }
        return $result;
    }

    public function getUsers(UserRequest $userRequest)
    {
        $userRole = json_encode(["ROLE_USER"]);
        $data = $this->userRepository->getAll($userRequest, $userRole);
        $users = $data['users'];
        $results = [];
        foreach ($users as $key => $user) {
            $results[$key] = $this->userTransformer->fromArray($user);
            $results[$key]['avatar'] = is_null($user->getAvatar()) ? null : $user->getAvatar()->getPath();
        }
        $results['totalPages'] = $data['totalPages'];
        $results['page'] = $data['page'];
        $results['totalUsers'] = $data['totalUsers'];

        return $results;
    }

    public function editRole(User $user, EditRoleRequest $editRoleRequest)
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
