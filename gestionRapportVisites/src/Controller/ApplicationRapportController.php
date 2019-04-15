<?php

namespace App\Controller;

use DateTime;
use App\Entity\Offrir;
use App\Entity\RapportVisite;
use App\Form\RapportVisiteType;
use App\Repository\RapportVisiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApplicationRapportController extends AbstractController
{


    /**
     * Visiblement, pas l'index
     * 
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('application_rapport/index.html.twig', [
            'controller_name' => 'ApplicationRapportController',
        ]);
    }

    /**
     * Index
     * 
     * @Route("/liste_rapport", name="listerapport")
     */
    public function Rapports(RapportVisiteRepository $repo,Request $request, ObjectManager $manager){

        $form = $this->createFormBuilder(array('message' => 'search'))
        ->add('search', TextType::class, [
            'attr' => [
                'class' => 'input is-hovered',
                'placeholder' => 'Rechercher par titre ou praticien'
            ],
            'label' => false,
            'required' => false
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->render('application_rapport/listerapport.html.twig', [
                'controller_name' => 'ApplicationRapportController',
                'rapports' =>  $repo->findManyByText($data['search']),
                'sForm' => $form->createView(),
            ]);
        }

        return $this->render('application_rapport/listerapport.html.twig', [
            'rapports' => $repo->findAll(),
            'sForm' => $form->createView(),
        ]);

    }

    /**
     * Montre un rapport détaillé
     *
     * @Route("/rapport/{id}", name="showRapport")
     * 
     * @return void
     */
    public function showRapport(RapportVisiteRepository $repo, $id)
    {
        return $this->render('application_rapport/showRapport.html.twig', [
            'rapport' => $repo->find($id),
        ]);
    }

    /**
     * Creation / Edition d'un rapport 
     * 
     * @Route("/create", name="ajoutrapport")
     * @route("/{id}/edit",  name="modifrapport")
     */
    public function form(RapportVisite $rapport=null, Request $request, ObjectManager $manager){
        if (!$rapport) {
            $rapport = new RapportVisite();
        }
        $form = $this->createForm(RapportVisiteType::class, $rapport);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rapport->setRapDate(new DateTime());
            dump($rapport);
            foreach($rapport->getOffrirs() as $offrir)
            {
                if(!$offrir->getOffQte())
                {
                    $rapport->removeOffrir($offrir);
                }
                else
                {
                    $manager->persist($offrir);
                }
            }
            // $rapport->setVisiteur($this->getUser());
            dump($rapport);
            $manager->persist($rapport);
            $manager->flush();

            return $this->redirectToRoute('listerapport');
        }

        return $this->render('application_rapport/create.html.twig',[
            'formRapportVisite' => $form->createView(),
            'editMode' => $rapport->getId()!== null
        ]);
    }

    /**
     * Suppression d'un rapport
     * 
     * @Route("/{id}/supprimer", name="removerapport")
     */
    public function remove(RapportVisiteRepository $repo, $id, ObjectManager $manager)
    {
        $rapport = $repo->find($id);
        dump($rapport);
        $manager->remove($rapport);
        $manager->flush();

        return $this->redirectToRoute('listerapport');
    }


}
