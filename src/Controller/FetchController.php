<?php

namespace App\Controller;

use App\Entity\ApodEntry;
use App\Service\ApodService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
