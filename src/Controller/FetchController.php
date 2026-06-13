<?php

namespace App\Controller;

use App\Entity\ApodEntry;
use App\Service\ApodService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class FetchController extends AbstractController
{
    #[Route('/fetch', name: 'fetch_apod')]
    public function fetch(
        ApodService $apodService,
        EntityManagerInterface $entityManager,
    ): Response {
        $data = $apodService->fetch();

        $existing = $entityManager
            ->getRepository(ApodEntry::class)
            ->findOneBy([
                'date' => $data['date'],
            ]);

        if ($existing) {
            return new Response('Entry already exists');
        }

        $entry = new ApodEntry();

        $entry->setDate($data['date']);
        $entry->setTitle($data['title']);
        $entry->setUrl($data['url']);
        $entry->setHdurl($data['hdurl'] ?? null);
        $entry->setMediaType($data['media_type']);
        $entry->setExplanation($data['explanation']);
        $entry->setCopyright($data['copyright'] ?? null);

        $entityManager->persist($entry);
        $entityManager->flush();

        return new Response('Entry saved successfully');
    }

    #[Route('/fetch/latest', name: 'api_apod_latest', methods: ['GET'])]
    public function latest(EntityManagerInterface $entityManager): JsonResponse
    {
        $entry = $entityManager
            ->getRepository(ApodEntry::class)
            ->findOneBy([], ['date' => 'DESC']);

        if (!$entry) {
            return new JsonResponse(['error' => 'No entries yet'], 404);
        }

        return $this->json([
            'title' => $entry->getTitle(),
            'date' => $entry->getDate(),
            'url' => $entry->getUrl(),
            'hdurl' => $entry->getHdurl(),
            'explanation' => $entry->getExplanation(),
            'mediaType' => $entry->getMediaType(),
            'copyright' => $entry->getCopyright(),
        ]);
    }

    #[Route('/fetch-range', name: 'fetch_apod_range')]
    public function fetchRange(
        ApodService $apodService,
        EntityManagerInterface $entityManager,
    ): Response {
        $centerDate = new \DateTime('2024-01-15'); // hardcoded for now

        $start = (clone $centerDate)->modify('-7 days')->format('Y-m-d');
        $end = (clone $centerDate)->modify('+7 days')->format('Y-m-d');

        $entries = $apodService->fetchRange($start, $end);

        $saved = 0;
        $skipped = 0;

        foreach ($entries as $data) {
            $existing = $entityManager
                ->getRepository(ApodEntry::class)
                ->findOneBy(['date' => $data['date']]);

            if ($existing) {
                $skipped++;
                continue;
            }

            $entry = new ApodEntry();
            $entry->setDate($data['date']);
            $entry->setTitle($data['title']);
            $entry->setUrl($data['url']);
            $entry->setHdurl($data['hdurl'] ?? null);
            $entry->setMediaType($data['media_type']);
            $entry->setExplanation($data['explanation']);
            $entry->setCopyright($data['copyright'] ?? null);

            $entityManager->persist($entry);
            $saved++;
        }

        $entityManager->flush();

        return new Response("Saved: $saved, Skipped: $skipped");
    }
}
