<?php

namespace Sententiaregum\Bundle\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sententiaregum\Bridge\User\DTO\AuthDTO;
use Sententiaregum\Bundle\UserBundle\Exception\FailedAuthenticationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * This controller handles the authentication requests
 */
class AuthenticationController extends Controller
{
    /**
     * Checks the user's credentials and generates an api key
     *
     * @param Request $request
     *
     * @return mixed[]
     *
     * @throws FailedAuthenticationException If the auth process has failed
     *
     * @Route("/auth/api_key.{_format}", name="sen_user_auth", requirements={"_format":"\w+"})
     * @Method({"GET", "POST"})
     * @View()
     * @ApiDoc(
     *     resource=true,
     *     description="This action generates api keys if the user is able to authenticate himself with credentials",
     *     statusCodes={
     *         200="The action was successful",
     *         401="The auth processor denies access"
     *     },
     *     requirements={
     *         {
     *             "name"="_format",
     *             "dataType"="string",
     *             "requirement"="\w+",
     *             "description" = "Type of the request (may be html or json)"
     *         }
     *     }
     * )
     */
    public function authenticateTokenAction(Request $request)
    {
        $credentials = new AuthDTO(null, null);
        $form        = $this->createForm('sen_user_form_auth', $credentials);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            /** @var \Sententiaregum\Bridge\User\Service\AuthenticationInterface $authService */
            $authService = $this->get('sen.user.auth.api_key_auth');
            $authService->setRequesterIp($request->getClientIp());

            $result = $authService->signIn($credentials);
            if ($result->isFailed()) {
                /** @var \Symfony\Component\Translation\TranslatorInterface $translator */
                $translator = $this->get('translator');

                throw new FailedAuthenticationException($translator->trans($result->getFailReason()));
            }

            $entityManager->flush();
            return ['apiKey' => $result->getUser()->getToken()->getApiKey()];
        }
    }
}
