<?php


namespace App\Action\Command;


use App\Domain\Model\DoneTask as DoneTaskDto;
use App\Entity\Task;
use App\Entity\User;
use App\Serializer\SafeSerializer;
use App\Serializer\SafeSerializerValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoneTask
{
    /**
     * @Route("/tasks/done", name="done_task", methods={"POST"})
     * @param Request $request
     * @param SafeSerializer $safeSerializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws SafeSerializerValidationException
     */
    public function __invoke(Request $request, SafeSerializer $safeSerializer, EntityManagerInterface $entityManager)
    {
        $doneTaskDto = $safeSerializer->safeDeserialize($request->getContent(), DoneTaskDto::class);

        /* @var Task $task */
        $task = $entityManager->getRepository(Task::class)->findOneBy(['id' => $doneTaskDto->taskId]);
        /* @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $doneTaskDto->userId]);
        if (is_null($task) || is_null($user)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $user->setPoints($user->getPoints() + $task->getPoint());
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}