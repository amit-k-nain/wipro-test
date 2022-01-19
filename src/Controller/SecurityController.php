<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ApiTokenRepository;
use App\Entity\ApiToken;

class SecurityController extends AbstractController
{
    private $apiTokenRepository;

    public function __construct(ApiTokenRepository $apiTokenRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request)
    {
        $clientIp = $request->getClientIp();
        $result = $this->apiTokenRepository->findByClientIp($clientIp);
        if(empty($result)) {
            $token = new ApiToken($clientIp);
            $em = $this->getDoctrine()->getManager();
            $em->persist($token);
            $em->flush();
            $result = $this->apiTokenRepository->findByClientIp($clientIp);
            $token = $result[0]->getToken();
            return new JsonResponse(['status' => 200, 'token' => $token, 'message' => 'Token Genrated Successfully.'], Response::HTTP_CREATED);
        }
        $check = $result[0]->isExpired();
        if ($check) {
            return new JsonResponse(['status' => 404, 'token' => null, 'message' => 'Token Expired. Please Re-Genrate.'], Response::HTTP_CREATED);
        } else {
            $token = $result[0]->getToken();
            return new JsonResponse(['status' => 200, 'token' => $token, 'message' => 'Already have a valid Token.'], Response::HTTP_CREATED);
        }
        
    }
}
