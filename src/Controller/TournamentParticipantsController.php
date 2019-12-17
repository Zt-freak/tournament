<?php

namespace App\Controller;

use App\Entity\TournamentParticipants;
use App\Form\TournamentParticipantsType;
use App\Repository\TournamentParticipantsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tournamentparticipants")
 */
class TournamentParticipantsController extends AbstractController
{
    /**
     * @Route("/", name="tournament_participants_index", methods={"GET"})
     */
    public function index(TournamentParticipantsRepository $tournamentParticipantsRepository): Response
    {
        return $this->render('tournament_participants/index.html.twig', [
            'tournament_participants' => $tournamentParticipantsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tournament_participants_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tournamentParticipant = new TournamentParticipants();
        $form = $this->createForm(TournamentParticipantsType::class, $tournamentParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tournamentParticipant);
            $entityManager->flush();

            return $this->redirectToRoute('tournament_participants_index');
        }

        return $this->render('tournament_participants/new.html.twig', [
            'tournament_participant' => $tournamentParticipant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournament_participants_show", methods={"GET"})
     */
    public function show(TournamentParticipants $tournamentParticipant): Response
    {
        return $this->render('tournament_participants/show.html.twig', [
            'tournament_participant' => $tournamentParticipant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tournament_participants_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TournamentParticipants $tournamentParticipant): Response
    {
        $form = $this->createForm(TournamentParticipantsType::class, $tournamentParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tournament_participants_index');
        }

        return $this->render('tournament_participants/edit.html.twig', [
            'tournament_participant' => $tournamentParticipant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournament_participants_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TournamentParticipants $tournamentParticipant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournamentParticipant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tournamentParticipant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tournament_participants_index');
    }
}
