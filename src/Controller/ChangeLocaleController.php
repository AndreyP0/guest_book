<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChangeLocaleController extends AbstractController
{
    /**
    * @Route("/change", name="change")
    */
    public function changeLocale(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('Language', ChoiceType::class, [
                'choices' => [
                    'English' => 'en_US',
                    'Ukraine' => 'ua_UA'
                    ]
            ])
            ->add('Save', SubmitType::class)
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted()) 
            {
                $em = $this->getDoctrine()->getManager();
                $locale = $form->getData()['locale'];
                $user = $this->getUser();
                $user->setLocale($locale);
                $em->persist($user);
                $em->flush();
            }

            return $this->render('locale/index.html.twig', [
                'form' => $form->createView()
            ]);
            
    }
}
