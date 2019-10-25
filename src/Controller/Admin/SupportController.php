<?php

namespace App\Controller\Admin;

use App\Entity\Support;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserSite;
use App\Firebrock\Command\AddSupportCommand;
use App\Firebrock\Command\ContactCommand;
use App\Firebrock\Command\GlobalParametersCommand;
use App\Firebrock\Command\PostCommand;
use App\Firebrock\Command\HomeCommand;
use App\Firebrock\Command\PresentationCommand;
use App\Firebrock\CommandHandler\AddSupportCommandHandler;
use App\Firebrock\CommandHandler\EditContactCommandHandler;
use App\Firebrock\CommandHandler\EditGlobalParametersCommandHandler;
use App\Firebrock\CommandHandler\EditPostCommandHandler;
use App\Firebrock\CommandHandler\EditHomeCommandHandler;
use App\Firebrock\CommandHandler\EditPresentationCommandHandler;
use App\Form\AddSupportType;
use App\Form\EditContactType;
use App\Form\EditGlobalParametersType;
use App\Form\EditHomeType;
use App\Form\EditPostType;
use App\Form\EditPresentationType;
use App\Form\AddSiteOptionsType;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SupportController
 * @Route("/bo-support")
 * @package App\Controller\Admin
 */
class SupportController extends AbstractController
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var UserRepository */
    private $userRepository;

    /**
     * SupportController constructor.
     * @param SiteRepository $siteRepository
     * @param UserRepository $userRepository
     */
    public function __construct(SiteRepository $siteRepository, UserRepository $userRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/", name="bo-support")
     * @param Request $request
     * @param AddSupportCommandHandler $addSupportCommandHandler
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function boSupport(Request $request, $success = false, AddSupportCommandHandler $addSupportCommandHandler)
    {
        $site = $this->siteRepository->getById($_SESSION['SITE_ID']);
        $user = $this->userRepository->get($this->getUser()->getId());
        $command = new AddSupportCommand();

        $form = $this->createForm(AddSupportType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $addSupportCommandHandler->handle($command, $user);
            $success = true;
        }

        return $this->render('admin/support/index.html.twig', [
            'controller_name' => 'SupportController',
            'site' => $site,
            'form' => $form->createView(),
            'success' => $success
        ]);
    }

}
