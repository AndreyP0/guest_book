<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminPanelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
    * @Route("/admin_panel", name="admin_panel")
    */
    public function admin(Request $request)
    {
        $user = new User();
        $user = $this->getUser()->getRol();

        if ($user !== 'admin') {
            return $this->redirectToRoute('book');
        }

        $result = $this->getDoctrine()->getRepository(User::class);
        $users = $result->findAll();


        return $this->render('admin/admin.html.twig', array(
            'users' => $users,
        ));
    }
}
