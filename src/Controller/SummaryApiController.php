<?php

namespace App\Controller;

use App\Entity\Summary;
use App\Repository\SummaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/cv', priority: 10000, format: 'json')]
class SummaryApiController extends AbstractController
{
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    private SummaryRepository $summaryRepository;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->validator = $validator;

        $this->summaryRepository = $em->getRepository(Summary::class);
    }

    /**
     * Получить все резюме
     */
    #[Route('/', name: 'app_summary_list')]
    public function index(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                $this->summaryRepository->findAll(),
                'json'
            ),
            json: true
        );
    }

    /**
     * Добавить новое резюме
     */
    #[Route('/add', name: 'app_summary_add')]
    public function add(Request $request): JsonResponse
    {
        /** @var Summary $summary */
        $summary = $this->serializer->deserialize(
            $request->getContent(),
            Summary::class,
            'json',
            [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['id', ['educations' => 'id']],
            ]
        );

        $errors = $this->validator->validate($summary);
        if (count($errors) > 0) {
            $errors = (string)$errors;

            return $this->error($errors, Response::HTTP_BAD_REQUEST);
        }

        return $this->saveEntity($summary);
    }

    /**
     * Получить резюме по id
     */
    #[Route('/{id}', name: 'app_summary_get')]
    public function get($id): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                $this->summaryRepository->findOneBy(['id' => $id]),
                'json'
            ),
            json: true
        );
    }

    /**
     * Редактировать резюме
     */
    #[Route('/{id}/edit', name: 'app_summary_edit')]
    public function edit($id, Request $request): JsonResponse
    {
        $summary = $this->summaryRepository->findOneBy(['id' => $id]);
        if ($summary === null) {
            return $this->error(
                'Cannot find summary with id=' . $id,
                Response::HTTP_NOT_FOUND
            );
        }

        /** @var Summary $newSummary */
        $newSummary = $this->serializer->deserialize(
            $request->getContent(),
            Summary::class,
            'json'
        );

        $errors = $this->validator->validate($newSummary);
        if (count($errors) > 0) {
            $errors = (string)$errors;

            return $this->error($errors, Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->summaryRepository->update($summary, $newSummary, true);
            return $this->ok();
        } catch (Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Обновить статус резюме
     */
    #[Route('/{id}/status/update', name: 'app_summary_update_status')]
    public function updateStatus($id, Request $request, DecoderInterface $decoder): JsonResponse
    {
        $summary = $this->summaryRepository->findOneBy(['id' => $id]);

        $status = $decoder->decode(
            $request->getContent(),
            'json',
        )['status'];

        $summary->setStatus($status);

        $errors = $this->validator->validate($summary, groups: ['status']);
        if (count($errors) > 0) {
            $errors = (string)$errors;

            return $this->error($errors, Response::HTTP_BAD_REQUEST);
        }

        return $this->saveEntity($summary);
    }

    /**
     * Сохранить резюме в БД
     */
    private function saveEntity(Summary $summary): JsonResponse
    {
        try {
            $this->summaryRepository->save($summary, true);
            return $this->ok();
        } catch (Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Успешный ответ
     */
    private function ok(): JsonResponse
    {
        return new JsonResponse('{"status": "ok"}', json: true);
    }

    /**
     * Ответ с возникнувшей ошибкой
     */
    private function error(string $message, int $status): JsonResponse
    {
        return new JsonResponse(['error' => $message], $status);
    }
}
