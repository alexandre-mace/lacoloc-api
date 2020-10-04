<?php


namespace App\Action\Command;


use App\Domain\Model\User as UserDto;
use App\Entity\Home;
use App\Entity\User;
use App\Serializer\SafeSerializer;
use App\Serializer\SafeSerializerValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateUser
{
    /**
     * @Route("/users", name="create_user", methods={"POST"})
     * @param Request $request
     * @param SafeSerializer $safeSerializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws SafeSerializerValidationException
     */
    public function __invoke(Request $request, SafeSerializer $safeSerializer, EntityManagerInterface $entityManager)
    {
        $userDto = $safeSerializer->safeDeserialize($request->getContent(), UserDto::class);

        $home = $entityManager->getRepository(Home::class)->findOneBy(['id' => $userDto->homeId]);
        if (is_null($home)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $user = $safeSerializer->safeDeserialize($userDto, User::class, 'object', [], new User());
        $home->addUser($user);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}