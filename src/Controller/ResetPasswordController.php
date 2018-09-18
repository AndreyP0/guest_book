<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset", name="reset_passwor")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
    	$user = new User();
        $new_pass = '';
        $form = $this->createFormBuilder(null)
            ->add('Email', EmailType::class)
            ->add('Reset', SubmitType::class)
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted()) 
            {
                $em = $this->getDoctrine()->getManager();
                $repository = $this->getDoctrine()->getRepository(User::class);
                $email = $form->getData()['Email'];
                $req = $repository->findOneBy(['email' => $email]);
                $name = $req->getUsername();

                    if ($req)
                    {
                        $random = random_int(1, 100);
                        $new_pass = '1Fm3mvdj6' . $random;

                        $new_password = $passwordEncoder->encodePassword($user, $new_pass);
                        $req->setPassword($new_password);
                        $em->persist($req);
                        $em->flush();
                    }
            }
    return $this->render('reset_password/reset.html.twig', array(
        'form' => $form->createView(),
        'new_password' => $new_pass,
    ));
    
    }
}