<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BookController extends AbstractController
{
	
	/**
     * @Route("/book", name="book")
     */
    public function book(PaginatorInterface $paginator)
    {
    	

    	$request = Request::createFromGlobals();

    	$entityManager = $this->getDoctrine()->getManager();

    	$message = new Message();

        //create form
    	$form = $this->createFormBuilder($message)
            ->add('Username', TextType::class, array(
            	'required'   => true,))
            ->add('Email', EmailType::class, array(
            	'required'   => true,))
            ->add('Homepage', UrlType::class)
            ->add('Text', TextareaType::class, array(
            	'required' => true,))
            ->add('Image', FileType::class)
            ->add('save', SubmitType::class, array(
            	'label' => 'Save message',
            	'attr' => array('class' => 'btn btn-success')))
            ->getForm();

            $form->handleRequest($request);

        // if form valid send to db
        if ($form->isSubmitted() && $form->isValid()){
        	$message = $form->getData();

        	$file = $message->getImage();
        	$fileImage = md5(uniqid()).'.'.$file->guessExtension();
        	$file->move($this->getParameter('upload_directory'), $fileImage);
        	$message->setImage($fileImage);

        	$message -> setUserIp($request->getClientIp());
        	$message -> setUserBrowser($_SERVER['HTTP_USER_AGENT']);
            $message -> setDate(date('H:i:s d/m/Y'));
    		
			$entityManager -> persist($message);
			$entityManager -> flush();

			return $this->redirectToRoute('book');
		}

		$repa = $this->getDoctrine()->getRepository(Message::class);
		$posts = $repa->findAll();

        //pagination
        $pagination = $paginator->paginate(
            $posts,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 25)
        );

        return $this->render('custom/book.html.twig', array(
            'form' => $form->createView(),
            'pagination' => $pagination
        ));
    }

    

   
}