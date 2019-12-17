<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Game;
use App\Entity\Participants;

/**
 * @Route("/tournament")
 */
class TournamentController extends AbstractController
{
    /**
     * @Route("/", name="tournament_index", methods={"GET"})
     */
    public function index(TournamentRepository $tournamentRepository): Response
    {
        return $this->render('tournament/index.html.twig', [
            'tournaments' => $tournamentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tournament_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tournament = new Tournament();
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tournament);
            $entityManager->flush();

            return $this->redirectToRoute('tournament_index');
        }

        return $this->render('tournament/new.html.twig', [
            'tournament' => $tournament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournament_show", methods={"GET"})
     */
    public function show(Tournament $tournament): Response
    {
        $participants = $tournament->getTournamentParticipants();

        return $this->render('tournament/show.html.twig', [
            'tournament' => $tournament,
            'participants' => $participants
        ]);
    }

    /**
     * @Route("/{id}/creatematchup", name="tournament_creatematchup", methods={"GET"})
     */
    public function createMatchup(Tournament $tournament): Response
    {
        $participants = $tournament->getTournamentParticipants();

        if (count($tournament->getGames()) >= 1) {
            // do something after round 1
        }
        else {
            $this->generateMatchup(1, $tournament);
        }
        //var_dump($tournament);

        return $this->render('tournament/creatematchup.html.twig', [

        ]);
    }

    /**
     * @Route("/{id}/edit", name="tournament_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tournament $tournament): Response
    {
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tournament_index');
        }

        return $this->render('tournament/edit.html.twig', [
            'tournament' => $tournament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournament_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tournament $tournament): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournament->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tournament);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tournament_index');
    }

    private function generateMatchup($round, $tournament)
    {
        // set the entitymanager
        $entityManager = $this->getDoctrine()->getManager();

        // set participants
        $participants = [];
        if ($round <=1) {
            $tournamentParticipants = $tournament->getTournamentParticipants();
            foreach ($tournamentParticipants as &$tournamentParticipant) {
                array_push($participants, $tournamentParticipant->getPlayer());
            }
            unset($tournamentParticipant);
        }
        else {
            // for rounds after round 1
            /*$tournament
            foreach ($arr as &$value) {
                $value = $value * 2;
            }
            unset($value);*/
        }

        shuffle($participants);

        $remainder = count($participants) % 2;
        if ($remainder != 0) {
            array_pop($participants);
        }

        // create new game and gameParticipants entities
        $participantNumber = 0;
        for ($i = 0; $i < count($participants) / 2; $i++) {

            $game = new Game();
            $game->setTournament($tournament);
            $game->setRound($round);
            $entityManager->persist($game);

            // set player 1
            $gameParticipant1 = new Participants();
            $gameParticipant1->setGame($game);
            $gameParticipant1->setPlayer($participants[$participantNumber]);
            $entityManager->persist($gameParticipant1);
            $participantNumber++;

            // set player 2
            $gameParticipant2 = new Participants();
            $gameParticipant2->setGame($game);
            $gameParticipant2->setPlayer($participants[$participantNumber]);
            $entityManager->persist($gameParticipant2);
            $participantNumber++;

            $entityManager->flush();
        }
    }
}
