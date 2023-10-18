<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Student;
use App\Form\ClassroomType;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/classroom')]
class ClassroomController extends AbstractController
{
    #[Route('', name: 'app_classroom')]
    public function index(ClassroomRepository $repo): Response
    {
        $result = $repo->findAll();
        return $this->render('classroom/index.html.twig', [
            'response' => $result,
        ]);

    }

    #[Route('/allMember/{idClassroom}', name: 'app_classroom_show')]
    public function show(ClassroomRepository $repo,int $idClassroom): Response
    {
        $result = $repo->find($idClassroom);
        return $this->render('show.html.twig', [
            'response' => $result,
        ]);

    }

    #[Route('/addMember/{id}', name: 'app_classroom_add')]
    public function add(ManagerRegistry $mr,Request $request,int $id,ClassroomRepository $repo): Response
    {
        $s= new Student();
        $form = $this->createForm(StudentType::class,$s);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $mr->getManager();
            $s->setClassroom($repo->find($id));
            $em->persist($s);
            $em->flush();
            return $this->redirectToRoute('app_classroom');
        }

        return $this->render('classroom/add.html.twig', [
            'classroomForm' => $form->createView(),
        ]);
    }

    #[Route('/addClassroom', name: 'app_classroom_addClassroom')]
    public function addClassroom(ManagerRegistry $mr,Request $request): Response
    {
        $c= new Classroom();
        $form = $this->createForm(ClassroomType::class,$c);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $mr->getManager();
            $c->setCreatedAt(new \DateTimeImmutable());
            $em->persist($c);
            $em->flush();
            return $this->redirectToRoute('app_classroom');
        }


        return $this->render('classroom/addClassroom.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_classroom_delete')]
    public function delete(ManagerRegistry $mr,int $id,ClassroomRepository $repo): Response
    {
        $result = $repo->find($id);
        foreach ($result->getStudents() as $student){
            $em = $mr->getManager();
            $student->setClassroom(null);
            $em->flush();
        }



        $em = $mr->getManager();
        $em->remove($result);
        $em->flush();
        return $this->redirectToRoute('app_classroom');
    }


}
