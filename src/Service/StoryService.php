<?php
namespace App\Service;

use App\Entity\Story;
use App\Repository\StoryRepository;

class StoryService
{
    private StoryRepository $storyRepository;

    public function __construct( StoryRepository $storyRepository)
    {
        $this->storyRepository = $storyRepository;
    }

    public function getAvailableStories() : array
    {
        return $this->storyRepository->findBy( [
            'isTrash' => false,
            'isDraft' => false,
            'privacy' => Story::PRIVACY_PUBLIC,
        ], ['publishedAt' => 'DESC'] );
    }

    public function getAllStories() : array
    {
        return $this->storyRepository->findBy( [
            'isTrash' => false,
            'isDraft' => false,
        ], ['publishedAt' => 'DESC'] );
    }
}