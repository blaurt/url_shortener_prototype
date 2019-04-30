<?php

declare(strict_types=1);

namespace App\Http\Controller;

use App\Model\Link\Entity\Link;
use App\Model\Link\ValueObject\Token;
use App\Model\Link\ValueObject\Url;
use App\Repository\LinkRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class LinkController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LinkRepository
     */
    private $linkRepository;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param EntityManagerInterface $em
     * @param LinkRepository $linkRepository
     * @param Environment $twig
     * @param RouterInterface $router
     */
    public function __construct(
        EntityManagerInterface $em,
        LinkRepository $linkRepository,
        Environment $twig,
        RouterInterface $router
    ) {
        $this->em = $em;
        $this->linkRepository = $linkRepository;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @Route("/", name="view_links")
     */
    public function viewLinks()
    {
        return new Response($this->twig->render('links.html.twig'));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws NonUniqueResultException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @Route("/links/generate", name="generate_links")
     */
    public function createLink(Request $request)
    {
        if (!$request->get('url')) {
            return new RedirectResponse($this->router->generate('view_links'));
        }

        $url = new Url($request->get('url'));

        /** @var Link $existsLink */
        $existsLink = $this->linkRepository->findLinkByUrl($url);

        if (!$existsLink) {
            $link = new Link($url, new Token());

            $this->em->persist($link);
            $this->em->flush();

            return new Response($this->twig->render('links.html.twig', [
                'code' => $link->getToken()
            ]));
        }

        return new Response($this->twig->render('links.html.twig', [
            'code' => $existsLink->getToken()->getValue()
        ]));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws NonUniqueResultException
     * @throws DBALException
     *
     * @Route("/{token}", name="redirect")
     */
    public function redirectToSite(Request $request)
    {
        /** @var Link $link */
        $link = $this->linkRepository->findUrlByToken($request->get('token'));

        if ($link === null) {
            return new RedirectResponse('https://proglib.io');
        }

        $this->linkRepository->updateViews($link->getUrl());

        return new RedirectResponse($link->getUrl()->getValue());
    }
}