<?php


namespace App\Action\Query;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class GetUsers
{
    /**
     * @Route("/users", name="get_users", methods={"GET"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function __invoke(Request $request, UserRepository $userRepository, SerializerInterface  $serializer)
    {
        $userEntities = $userRepository->findAll();
        $users = [];
        foreach ($userEntities as $userEntity) {
            $users[] = $serializer->normalize(
                $userEntity,
                null,
                [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getName();
                }]
            );
        }
        return new JsonResponse($users, Response::HTTP_OK);
    }
}