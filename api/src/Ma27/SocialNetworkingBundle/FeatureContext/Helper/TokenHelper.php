<?php
namespace Ma27\SocialNetworkingBundle\FeatureContext\Helper;

use Ma27\SocialNetworkingBundle\Controller\TokenController;
use Ma27\SocialNetworkingBundle\Entity\User\Api\UserRepositoryInterface;
use Ma27\SocialNetworkingBundle\Util\Api\PasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TokenHelper
{
    protected $tokenController;
    protected $userRepo;
    protected $hasher;

    public function __construct(
        TokenController $tokenController,
        UserRepositoryInterface $userRepository,
        PasswordHasherInterface $hasher)
    {
        $this->tokenController = $tokenController;
        $this->userRepo = $userRepository;
        $this->hasher = $hasher;
    }

    /**
     * @param string[] $credentials
     * @return mixed
     */
    public function requestToken(array $credentials)
    {
        $request = Request::create('/');
        $request->attributes->add($credentials);

        return $this->generateResult($this->tokenController->requestTokenAction($request));
    }

    public function createDummyUser()
    {
        $user = $this->userRepo->create('Ma27', $this->hasher->create('foobar'), 'Ma27@example.org', new \DateTime());

        $this->userRepo->add($user);
        return $this->userRepo->findByName('Ma27');
    }

    public function flush()
    {
        $this->userRepo->flush();
    }

    /**
     * @param JsonResponse $response
     * @return mixed
     */
    private function generateResult(JsonResponse $response)
    {
        return json_decode($response->getContent(), true);
    }
}
 