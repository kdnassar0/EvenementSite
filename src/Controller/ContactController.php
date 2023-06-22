<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\OrgainsateurContactType;
use App\Repository\EvenementRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{ 
    /**
     * @Route("/contact/{id}", name="app_contact")
     */
    public function index(Request $request,MailerInterface $mailer,EvenementRepository $evenement,$id): Response
    {
        $form =$this->createForm(OrgainsateurContactType::class);
        $contact = $form->handleRequest($request);

         $evenement = $evenement->find($id);

        if($form->isSubmitted() && $form->isValid()){
            
            $email =(new TemplatedEmail())
               ->from($contact->get('email')->getData())
               ->subject('Contact au sujet de votre evenement "'.$evenement->getNom() .'"')
               ->to($evenement->getCreateur()->getEmail())

               ->htmlTemplate('email/contact.html.twig')

               ->context([
                'evenement'=>$evenement->getNom(),
                'e_mail' => $contact->get('email')->getData(),
                'message' =>$contact->get('message')->getData()
               ]);
               $mailer->send($email);
               return $this->redirectToRoute('app_categorie');
               

        }
        return $this->render('contact/index.html.twig', [
          'formContact'=>$form->createView()
        ]);
    }
}
