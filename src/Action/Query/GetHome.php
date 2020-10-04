<?php


namespace App\Action\Query;


use App\Repository\HomeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GetHome
{
    /**
     * @Route("/homes/{id}", name="get_home", methods={"GET"})
     * @param Request $request
     * @param HomeRepository $homeRepository
     * @param SerializerInterface $serializer
     * @param $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, HomeRepository $homeRepository, SerializerInterface  $serializer, $id)
    {
        $home = $homeRepository->findOneBy(['id' => $id]);
        if (is_null($home)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($serializer->normalize($home), Response::HTTP_OK);
    }
}