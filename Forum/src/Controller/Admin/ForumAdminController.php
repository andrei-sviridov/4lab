<?php

namespace App\Controller\Admin;

use App\Entity\Topic;
use App\Entity\Comment;
use App\Entity\Section;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class ForumAdminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
		// redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(TopicCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Forum');
    }

    public function configureMenuItems(): iterable
    {
		yield MenuItem::linkToCrud('Топики', 'fas fa-list', Topic::class);
		yield MenuItem::linkToCrud('Комменты', 'fas fa-list', Comment::class);
		yield MenuItem::linkToCrud('Разделы', 'fas fa-list', Section::class);
		yield MenuItem::linkToCrud('Пользователи', 'fas fa-list', User::class);
    }
}
