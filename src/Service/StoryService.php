<?php
namespace App\Service;

use App\Entity\Story;
use App\Entity\User;
use App\Repository\StoryRepository;
use Symfony\Contracts\Cache\CacheInterface;

class StoryService
{
    private string $CACHE_KEY = 'stories';

    public function __construct( private readonly StoryRepository $storyRepository, private readonly CacheInterface $cache)
    {
    }

    public function getAvailableStories( ?string $limit = null ) : array
    {
        return $this->storyRepository->findBy( [
            'isTrash' => false,
            'isDraft' => false,
            'privacy' => Story::PRIVACY_PUBLIC,
        ], ['publishedAt' => 'DESC'] , $limit );
    }

    public function getAllStories() : array
    {
        return $this->storyRepository->findBy( [
            'isTrash' => false,
            'isDraft' => false,
        ], ['publishedAt' => 'DESC'] );
    }

    public function createStory( Story $story, ?User $author ) : void
    {
        if ( empty( $story->getDescription() ) ) {
            throw new \InvalidArgumentException( 'Description cannot be empty' );
        }

        if ( $story->getPrivacy() === Story::PRIVACY_PUBLIC ) {
            $story->setPublishedAt( new \DateTimeImmutable() );
        }

        $story->setAuthor( $author );

        $this->storyRepository->save( $story, true );
    }

    public function update( Story $story ) : void
    {
        $story->setUpdatedAt( new \DateTimeImmutable() );
        $this->storyRepository->save( $story );
    }

    public function moveToTrash( Story $story ) : void
    {
        $story->setIsTrash( true );
        $this->storyRepository->save( $story );
    }

    public function delete( Story $story ) : void
    {
        $this->storyRepository->remove( $story );
    }

}