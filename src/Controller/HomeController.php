<?php

namespace App\Controller;

use App\Form\UserProfileType;
use App\Service\StoryService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HomeController extends AbstractController
{

    public function __construct( private readonly StoryService $storyService, private CacheInterface $cache )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/', name: 'app_home')]
    public function homepage(): Response
    {

        // mise en cache de la liste des histoires pour 1h
        $stories = $this->cache->get('stories', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->storyService->getAvailableStories(4);
        });

        return $this->render('home/index.html.twig', [
            'stories' => $stories,
        ]);
    }

    #[Route('/changer-la-langue/{locale}', name: 'app_change_language')]
    public function changeLanguage( $locale, Request $request ): Response
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/mentions-legales', name: 'app_legal_notice')]
    public function legalNotice(): Response
    {
        return $this->render('home/legal_notice.html.twig');
    }

    #[Route('/a-propos', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }

    #[Route('/cgu', name: 'app_terms_of_use')]
    public function termsOfUse(): Response
    {
        return $this->render('home/terms_of_use.html.twig');
    }

    #[Route('/politique-de-confidentialite', name: 'app_privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('home/privacy_policy.html.twig');
    }

    #[Route('/profil', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserProfileType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setUpdatedAt(new DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a bien été mis à jour');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('account/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('home/dashboard.html.twig');
    }

}
