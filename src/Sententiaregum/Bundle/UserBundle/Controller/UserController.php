<?php

/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

namespace Sententiaregum\Bundle\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sententiaregum\Bundle\UserBundle\DTO\CreateUserDTO;
use Sententiaregum\Bundle\UserBundle\Exception\UserCrudException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * User CRUD controller
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     *
     * @return string[]|\Symfony\Component\Form\Form
     *
     * @throws UserCrudException If the creation fails
     *
     * @Method({"POST"})
     * @Route("/user", name="sen_user_create")
     * @View(statusCode=201)
     * @ApiDoc(
     *     resource=true,
     *     description="Creates a new user",
     *     statusCodes={
     *         201="User was created",
     *         400="Invalid data entered",
     *         500="Internal error occurred"
     *     }
     * )
     */
    public function createAction(Request $request)
    {
        $dto  = new CreateUserDTO(null, null, null, $this->container->getParameter('sen.user.param.default_roles'));
        $form = $this->createForm('sen_user_form_create');

        $form->handleRequest($request);
        if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            /** @var \Sententiaregum\Bundle\UserBundle\Service\User $userService */
            $userService   = $this->get('sen.user.crud.service');
            /** @var \Sententiaregum\Bundle\UserBundle\Repository\UserRepository $repository */
            $repository    = $this->get('sen.user.repository');

            if (!$userService->create($dto)) {
                throw new UserCrudException('Unable to create user!');
            }

            $entityManager->flush();
            #ToDo: use user create dto
            return [
                'id'       => $repository->findOneByName($dto->getUsername())->getUserId(),
                'location' => $this->generateUrl('sen_user_get', ['userId' => 5])
            ];
        }

        #ToDo: dispatch "invalid input" event
        return $form;
    }

    /**
     * @param integer $userId
     *
     * @return mixed[]
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If the user does not exist
     *
     * @Method({"GET"})
     * @Route("/user/{userId}", name="sen_user_get", requirements={"userId"="\w+"})
     * @View()
     * @ApiDoc(
     *     resource=true,
     *     description="Searches a user by its id",
     *     statusCodes={
     *         200="User was found",
     *         404="User does not exist"
     *     },
     *     requirements={
     *         {
     *             "name"="userId",
     *             "description"="Id of the searched user",
     *             "requirements"="\d+",
     *             "dataType"="integer"
     *         }
     *     }
     * )
     */
    public function getUserAction($userId)
    {
        /** @var \Sententiaregum\Bundle\UserBundle\Repository\UserRepository $repository */
        $repository = $this->container->get('sen.user.repository');

        $user = $repository->find($userId);
        if (!$user) {
            throw $this->createNotFoundException(
                sprintf('User with id %d was not found!', $userId)
            );
        }

        return $user;
    }
}
