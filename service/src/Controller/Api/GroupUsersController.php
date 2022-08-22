<?php

namespace App\Controller\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UserGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[AsController]
class GroupUsersController extends AbstractController
{
    #[Route(
        path: '/api/group_users/{id}', 
        name: 'api_group_users',
        methods: ['GET'],
        defaults: [
            '_api_resource_class' => User::class,
            '_api_item_operation_name' => 'api_group_users',
        ],
    )]
    public function __invoke(UserGroup $userGroup, Request $request, UserRepository $userRepository): Paginator
    {
        $page = $request->query->get('page', 1);

        return $userRepository->getGroupUsers($page, $userGroup->getId());
    }
}
