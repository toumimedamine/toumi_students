<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Student;
use App\Form\ClubType;
use App\Form\StudentType;
use App\Repository\ClubRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/club')]
class ClubController extends AbstractController
{
    #[Route(name: 'app_club')]
    public function index(ClubRepository $repo): Response
    {
        $result = $repo->findAll();
        return $this->render('club/index.html.twig', [
            'response' => $result,
        ]);

    }

    #[Route('/allStudents/{idClub}', name: 'app_club_show_club')]
    public function show(ClubRepository $repo,int $idClub): Response
    {

        $result = $repo->find($idClub);
        return $this->render('show.html.twig', [
            'response' => $result,
        ]);

    }

    #[Route('/add/{idClub}', name: 'app_club_add')]
    public function add(ClubRepository $repo,int $idClub,Request $request,ManagerRegistry $mr): Response
    {
        $s=new Student();
        $form = $this->createForm(StudentType::class, $s);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $club = $repo->find($idClub);
            $club->addStudent($s);
            $em = $mr->getManager();
            $em->persist($s);
            $em->flush();
            $em->persist($club);
            $em->flush();
            return $this->redirectToRoute('app_club');
        }

        return $this->render('club/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/addClub', name: 'app_club_addclub')]
    public function addClub(Request $request,ManagerRegistry $mr): Response
    {
        $c=new Club();
        $form = $this->createForm(ClubType::class, $c);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $c->setCreatedAt(new \DateTimeImmutable());
            $em = $mr->getManager();
            $em->persist($c);
            $em->flush();
            return $this->redirectToRoute('app_club');
        }


        return $this->render('club/addClub.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/remove/{id}', name: 'app_club_rmv')]
    public function remove(ClubRepository $repo,ManagerRegistry $mr,string $id):Response{
        $result = $repo->find($id);

        $em = $mr->getManager();

        $em->remove($result);
        $em->flush();

        return $this->redirectToRoute('app_club');
    }


}
