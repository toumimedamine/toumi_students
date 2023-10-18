<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Club;
use App\Entity\Student;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/addStatic', name: 'app_student_add_static')]
    public function createStatic(ManagerRegistry $mr): Response
    {

        $s = new Student();
        $s->setName('oussna');
        $s->setLastName('saoudi');
        $s->setEmail('oussna@gmail.com');
        $s->setPhone(2591997);

        $em = $mr->getManager();
        $em->persist($s);
        $em->flush();

        $s1 = new Student();
        $s1->setName('mannoo');
        $s1->setLastName('mzoughi');
        $s1->setEmail('mannoo@gmail.com');
        $s1->setPhone(56889626);

        $em->persist($s1);
        $em->flush();

        $c= new Classroom();
        $c->setName('class1');
        $c->setProf('prof1');
        $c->setCreatedAt(new \DateTimeImmutable());

        $em->persist($c);
        $em->flush();

        $s->setClassroom($c);
        $s1->setClassroom($c);
        $em->persist($s);
        $em->persist($s1);
        $em->flush();

        $club = new Club();
        $club->setName('club1');
        $club->setDescription('club1 description');
        $club->setCreatedAt(new \DateTimeImmutable());

        $em->persist($club);
        $em->flush();

        $s->addClub($club);
        $em->persist($s);
        $em->flush();

        return new Response('Students created');
    }
}
