<?php

/**
 * This file is part of a diversworld Contao Bundle.
 *
 * (c) Eckhard becker 2020 <info@diversworld.eu>
 * @author     Eckhard becker
 * @package    Course Manager
 * @license    GPL-3.0-or-later
 * @see        https://github.com/diversworld/contao-coursemanager-bundle
 *
 */

declare(strict_types=1);

namespace Diversworld\ContaoCoursemanagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

/**
 * Class MyCustomController
 *
 * @package Symfony
 *
 * @Route("/my_custom",
 *     name="diversworld_contao_coursemanager_my_custom",
 *     defaults={
 *         "_scope" = "frontend",
 *         "_token_check" = true
 *     }
 * )
 */
class MyCustomController extends AbstractController
{
    /** @var TwigEnvironment */
    private $twig;

    /**
     * MyCustomController constructor.
     * @param TwigEnvironment $twig
     */
    public function __construct(TwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke()
    {
        $animals = [

            [
                'species' => 'dogs',
                'color'   => 'white'
            ],
            [
                'species' => 'birds',
                'color'   => 'black'
            ], [
                'species' => 'cats',
                'color'   => 'pink'
            ], [
                'species' => 'cows',
                'color'   => 'yellow'
            ],
        ];

        return new Response($this->twig->render(
            '@DiversworldContaoCoursemanager/MyCustom/my_custom.twig',
            [
                'animals' => $animals,
            ]
        ));
    }
}
