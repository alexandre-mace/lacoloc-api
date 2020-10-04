<?php


namespace App\Action\Query;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class GetUser
{
    /**
     * @Route("/users/{id}", name="get_user", methods={"GET"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, UserRepository $userRepository, SerializerInterface  $serializer, $id)
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        if (is_null($user)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            $serializer->normalize(
                $user,
                null,
                [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getName();
                }]
            ),
            Response::HTTP_OK
        );
    }
}