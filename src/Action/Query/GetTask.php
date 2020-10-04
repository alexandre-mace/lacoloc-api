<?php


namespace App\Action\Query;


use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GetTask
{
    /**
     * @Route("/tasks/{id}", name="get_task", methods={"GET"})
     * @param Request $request
     * @param TaskRepository $taskRepository
     * @param SerializerInterface $serializer
     * @param $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, TaskRepository $taskRepository, SerializerInterface  $serializer, $id)
    {
        $task = $taskRepository->findOneBy(['id' => $id]);
        if (is_null($task)) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($serializer->normalize($task), Response::HTTP_OK);
    }
}