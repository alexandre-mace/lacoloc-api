<?php


namespace App\Action\Query;


use App\Repository\HomeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GetHomes
{
    /**
     * @Route("/homes", name="get_homes", methods={"GET"})
     * @param Request $request
     * @param HomeRepository $homeRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function __invoke(Request $request, HomeRepository $homeRepository, SerializerInterface  $serializer)
    {
        $homeEntities = $homeRepository->findAll();
        $homes = [];
        foreach ($homeEntities as $homeEntity) {
            $homes[] = $serializer->normalize($homeEntity);
        }
        return new JsonResponse($homes, Response::HTTP_OK);
    }
}