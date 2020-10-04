<?php


namespace App\Action\Command;


use App\Domain\Model\Home as HomeDto;
use App\Entity\Home;
use App\Serializer\SafeSerializer;
use App\Serializer\SafeSerializerValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateHome
{
    /**
     * @Route("/homes", name="create_home", methods={"POST"})
     * @param Request $request
     * @param SafeSerializer $safeSerializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws SafeSerializerValidationException
     */
    public function __invoke(Request $request, SafeSerializer $safeSerializer, EntityManagerInterface $entityManager)
    {
        $homeDto = $safeSerializer->safeDeserialize($request->getContent(), HomeDto::class);
        $homeDto->uuid = bin2hex(random_bytes(10));
        $home = $safeSerializer->safeDeserialize($homeDto, Home::class, 'object', [], new Home());
        $entityManager->persist($home);
        $entityManager->flush();

        return new JsonResponse(['uuid' => $home->getUuid()], Response::HTTP_CREATED);
    }
}