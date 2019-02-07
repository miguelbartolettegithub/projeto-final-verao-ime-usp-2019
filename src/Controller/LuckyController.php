<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Psr\Log\LoggerInterface;

class LuckyController extends AbstractController {

    /**
      * @Route("/lucky/number", name="app_lucky_number")
    */
    public function number() {
        $number = random_int(0, 100);

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }

    /**
      * @Route("/lucky/number/{numero}", requirements={"numero"="\d+"})
    */
	public function meuNumero($numero, LoggerInterface $logger, Request $request) {
		$logger->info('We are logging!');

		$id = $request->query->post('id', 99);

		return $this->render('lucky/number.html.twig', [
	            'number' => $number,
        	]);	
	}

/**
      * @Route("/lucky/login")
    */
	public function login(SessionInterface $session, Request $request) {
	//$session->set('usuario', 'diogo');
	//$session->set('senhaCrypto', 'diogo@teste.com');

	$usuario = $session->get('usuario');
	
	//$this->addFlash('notice','Your changes were saved!');
	$request->getSession()->getFlashBag()->get('notice');
	//print_r($_SESSION);
	return $this->json(['username' => 'jane.doe']);
/*	return new Response(
            ''
        );
*/
	}

}

