<?php


namespace App\Action\Query;


use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class GetTasks
{
    /**
     * @Route("/tasks", name="get_tasks", methods={"GET"})
     * @param Request $request
     * @param TaskRepository $taskRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function __invoke(Request $request, TaskRepository $taskRepository, SerializerInterface  $serializer)
    {
        $taskEntities = $taskRepository->findAll();
        $tasks = [];
        foreach ($taskEntities as $taskEntity) {
            $tasks[] = $serializer->normalize(
                $taskEntity,
                null,
                [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getName();
                }]
            );
        }
        return new JsonResponse($tasks, Response::HTTP_OK);
    }
}