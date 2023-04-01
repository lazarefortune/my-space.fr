<?php

namespace App\Controller;

use App\Entity\Story;
use App\Form\StoryType;
use App\Repository\StoryRepository;
use App\Service\StoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route( '/histoires' )]
class StoryController extends AbstractController
{
    public function __construct( private readonly StoryService $storyService ){}

    #[Route( '', name: 'app_story_index', methods: ['GET'] )]
    public function index() : Response
    {
        return $this->render( 'story/index.html.twig', [
            'stories' => $this->storyService->getAvailableStories(),
        ] );
    }

    #[Route( '/nouvelle', name: 'app_story_new', methods: ['GET', 'POST'] )]
    #[isGranted( 'ROLE_USER' )]
    public function new( Request $request, StoryRepository $storyRepository ) : Response
    {
        $story = new Story();
        $form = $this->createForm( StoryType::class, $story );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {

            if ( empty( $story->getDescription() ) ) {
                $this->addFlash( 'danger', 'On a tous une histoire à raconter, non ? la tienne est vide !' );
                return $this->redirectToRoute( 'app_story_new' );
            }

            if ( $story->getPrivacy() === Story::PRIVACY_PUBLIC ) {
                $story->setPublishedAt( new \DateTimeImmutable() );
            }

            $story->setAuthor( $this->getUser() );


            $storyRepository->save( $story, true );

            return $this->redirectToRoute( 'app_story_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->render( 'story/new.html.twig', [
            'story' => $story,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'app_story_show', methods: ['GET'] )]
    public function show( Story $story ) : Response
    {
        $this->denyAccessUnlessGranted( 'show', $story );

        return $this->render( 'story/show.html.twig', [
            'story' => $story,
        ] );
    }

    #[Route( '/{id}/edition', name: 'app_story_edit', methods: ['GET', 'POST'] )]
    #[isGranted( 'ROLE_USER' )]
    public function edit( Request $request, Story $story, StoryRepository $storyRepository ) : Response
    {

        $this->denyAccessUnlessGranted( 'edit', $story );

        $form = $this->createForm( StoryType::class, $story );
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            $storyRepository->save( $story, true );

            return $this->redirectToRoute( 'app_story_index', [], Response::HTTP_SEE_OTHER );
        }

        return $this->render( 'story/edit.html.twig', [
            'story' => $story,
            'form' => $form,
        ] );
    }

    #[Route( '/{id}', name: 'app_story_delete', methods: ['POST'] )]
    #[isGranted( 'ROLE_USER' )]
    public function delete( Request $request, Story $story, StoryRepository $storyRepository ) : Response
    {
        if ( $this->isCsrfTokenValid( 'delete' . $story->getId(), $request->request->get( '_token' ) ) ) {
            $storyRepository->remove( $story, true );
        }

        return $this->redirectToRoute( 'app_story_index', [], Response::HTTP_SEE_OTHER );
    }
}
