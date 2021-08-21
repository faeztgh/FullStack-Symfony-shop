<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * @Route("/ticket")
 */
class TicketController extends AbstractController
{
    private const SUBMITTED_STATUS = "submitted";
    private const EDITED_STATUS = "edited";
    private const WAITING_STATUS = "waiting";
    private const IN_PROGRESS_STATUS = "in_progress";
    private const DONE_STATUS = "done";
    /**
     * @var TranslatorInterface $translator
     */
    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="ticket_index", methods={"GET"})
     * @return Response
     * @throws Exception
     */
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class);
        $user = $user->findOneByUsername($this->getUser()->getUsername());

        return $this->render('ticket/index.html.twig', [
            'tickets' => $user->getTickets(),
        ]);
    }

    /**
     * @Route("/new", name="ticket_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function new(Request $request): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class);
        $user = $user->findOneByUsername($this->getUser()->getUsername());

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->setOwner($user);
            $ticket->setStatus(self::SUBMITTED_STATUS);
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="ticket_show", methods={"GET"})
     * @param Ticket $ticket
     * @return Response
     */
    public function show(Ticket $ticket): Response
    {
        $this->denyAccessUnlessGranted('show', $ticket);

        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="ticket_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Ticket $ticket
     * @param $_locale
     * @return Response
     */
    public function edit(Request $request, Ticket $ticket, $_locale): Response
    {
        $this->denyAccessUnlessGranted('edit', $ticket);

        if ($ticket->getStatus() === self::EDITED_STATUS || $ticket->getStatus() === self::SUBMITTED_STATUS) {

            $form = $this->createForm(TicketType::class, $ticket);
            $form->handleRequest($request);
            $ticket->setStatus(self::EDITED_STATUS);
            $ticket->setTranslatableLocale($_locale);
            if ($form->isSubmitted() && $form->isValid()) {
                $em=$this->getDoctrine()->getManager();
                $em->persist($ticket);
                $em->flush();

                return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('ticket/edit.html.twig', [
                'ticket' => $ticket,
                'form' => $form->createView(),
            ]);
        }
        $this->addFlash("error", $this->translator->trans('app.ticket.controller.you cannot remove this ticket'));
        return $this->redirectToRoute('ticket_index');
    }


    /**
     * @Route("/{id}", name="ticket_delete", methods={"POST"})
     * @param Request $request
     * @param Ticket $ticket
     * @return Response
     */
    public function delete(Request $request, Ticket $ticket): Response
    {
        if ($ticket->getStatus() !== self::SUBMITTED_STATUS && $ticket->getStatus() !== self::EDITED_STATUS) {
            $this->addFlash("error", $this->translator->trans('app.ticket.controller.You only can remove submitted tickets'));
            return $this->redirectToRoute('ticket_index');
        }
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ticket_index', [], Response::HTTP_SEE_OTHER);
    }
}
