<?php


namespace App\Action\Command;


use App\Domain\Model\Task as TaskDto;
use App\Entity\Home;
use App\Entity\Task;
use App\Entity\User;
use App\Serializer\SafeSerializer;
use App\Serializer\SafeSerializerValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CreateTask
{
    /**
     * @Route("/tasks", name="create_task", methods={"POST"})
     * @param Request $request
     * @param SafeSerializer $safeSerializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws SafeSerializerValidationException
     */
    public function __invoke(Request $request, SafeSerializer $safeSerializer, EntityManagerInterface $entityManager)
    {
        $taskDto = $safeSerializer->safeDeserialize($request->getContent(), TaskDto::class);

        $home = $entityManager->getRepository(Home::class)->findOneBy(['id' => $taskDto->homeId]);
        $addedByUser = $entityManager->getRepository(User::class)->findOneBy(['id' => $taskDto->userId]);
        if (is_null($home) || is_null($addedByUser)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        /* @var Task $task */
        $task = $safeSerializer->safeDeserialize(
            $taskDto,
            Task::class,
            'object',
            [],
            new Task()
        );
        $task->setAddedBy($addedByUser);
        $task->setHome($home);

        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}