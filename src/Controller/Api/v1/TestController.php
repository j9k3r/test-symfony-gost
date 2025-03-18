<?php

namespace App\Controller\Api\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route(path: '/api/v1')]
class TestController extends AbstractController
{
//    private $rateLimiterFactory;

//    public function __construct(RateLimiterFactory $apiRateLimiter)
//    {
//        $this->rateLimiterFactory = $apiRateLimiter;
//    }
    #[Route(path: '/test', methods: ['GET'])]
    public function testAction(Request $request): JsonResponse
    {

//        $limiter = $this->rateLimiterFactory->create($request->getClientIp());


//        $limit = $limiter->consume(1);

//        if (!$limit->isAccepted()) {
//            return new JsonResponse(['error' => 'Too many requests'], 429);
//        }

        return new JsonResponse(['message' => 'OK'], 200);
    }
}