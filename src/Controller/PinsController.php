<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	/**
	 * @Route("/", name="app_home", methods="GET")
	 */
	public function index(PinRepository $pinRepository): Response
	{
		$pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);

		return $this->render('pins/index.html.twig', compact('pins'));
	}

	/**
	 * @Route("/create", name="app_pins_create", methods="GET|POST")
	 */
	public function create(Request $request): Response
	{
		$pin = new Pin;

		$form = $this->createForm(PinType::class, $pin);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->persist($pin);
			$this->em->flush();
			return $this->redirectToRoute('app_home');
		}

		return $this->render('pins/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/pins/{id<\d+>}", name="app_pins_show", methods="GET")
	 */
	public function show(Pin $pin): Response
	{
		return $this->render('pins/show.html.twig', compact('pin'));
	}

	/**
	 * @Route("/pins/{id<\d+>}/edit", name="app_pins_edit", methods="GET|PUT")
	 */
	public function edit(Pin $pin, Request $request): Response
	{
		$form = $this->createForm(PinType::class, $pin, [
			'method' => 'PUT'
		]);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->flush();
			return $this->redirectToRoute('app_home');
		}

		return $this->render('pins/edit.html.twig', [
			'pin' => $pin,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/pins/{id<\d+>}/delete", name="app_pins_delete", methods="DELETE")
	 */
	public function delete(Pin $pin): Response
	{
		$this->em->remove($pin);
		$this->em->flush();
		return $this->redirectToRoute('app_home');
	}
}
