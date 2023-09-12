<?php

namespace App\Controller;

use App\Entity\Story;
use App\Form\StoryType;
use App\Service\StoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route( '/histoires' , name: 'app_story_')]
class StoryController extends AbstractController
{
    public function __construct( private readonly StoryService $storyService,private CacheInterface $cache )
    {
    }

    #[Route( '/', name: 'index', methods: ['GET'] )]
    public function index() : Response
    {
        // cache
        $stories = $this->cache->get( 'stories', function ( ItemInterface $item ) {
            $item->expiresAfter( 3600 );

            return $this->storyService->getAvailableStories();
        } );

        return $this->render( 'story/index.html.twig', [
            'stories' => $stories,
        ] );
    }

    #[Route( '/creation', name: 'new', methods: ['GET', 'POST'] )]
    #[isGranted( 'ROLE_USER' )]
    public function new( Request $request ) : Response
    {
        $story = new Story();
        $form = $this->createForm( StoryType::class, $story );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {

            try {
                $this->storyService->createStory( $story, $this->getUser() );

                $this->addFlash( 'success', 'Votre histoire a bien été créée' );
                return $this->redirectToRoute( 'app_story_index', [], Response::HTTP_SEE_OTHER );
            } catch ( \InvalidArgumentException $e ) {
                $this->addFlash( 'danger', $e->getMessage() );

                return $this->redirectToRoute( 'app_story_new' );
            }

        }

        return $this->render( 'story/new.html.twig', [
            'story' => $story,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'show', methods: ['GET'] )]
    public function show( Story $story ) : Response
    {
        $this->denyAccessUnlessGranted( 'show', $story );

        return $this->render( 'story/show.html.twig', [
            'story' => $story,
        ] );
    }

    #[Route( '/{id}/edition', name: 'edit', methods: ['GET', 'POST'] )]
    #[isGranted( 'ROLE_USER' )]
    public function edit( Request $request, Story $story ) : Response
    {

        $this->denyAccessUnlessGranted( 'edit', $story );

        $form = $this->createForm( StoryType::class, $story );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $this->storyService->update( $story );

            return $this->redirectToRoute( 'app_story_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->render( 'story/edit.html.twig', [
            'story' => $story,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}/corbeille', name: 'move_to_trash', methods: ['POST'] )]
    public function moveToTrash( Story $story ) : Response
    {
        $this->denyAccessUnlessGranted( 'moveToTrash', $story );

        $this->storyService->moveToTrash( $story );

        return $this->redirectToRoute( 'app_story_index', [], Response::HTTP_SEE_OTHER );
    }

    #[Route( '/{id}', name: 'delete', methods: ['POST'] )]
    #[isGranted( 'ROLE_USER' )]
    public function delete( Request $request, Story $story ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $story->getId(), $request->request->get( '_token' ) ) ) {
            $this->storyService->delete( $story );
        }

        return $this->redirectToRoute( 'app_story_index', [], Response::HTTP_SEE_OTHER );
    }
}
