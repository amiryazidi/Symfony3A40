<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\ClassroomRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudentController extends AbstractController
{
    #[Route('/student', name: 'app_student')]
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    #[Route('/fetch', name: 'fetch')]
    public function fetch(StudentRepository $studentRepository): Response
    {
        $result = $studentRepository->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $result,
        ]);
    }

    #[Route('/fetch2', name: 'fetch')]
    public function fetch2(ManagerRegistry $mr): Response
    {

        $em = $mr->getRepository(Student::class);
        $result = $em->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $result,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(ManagerRegistry $mr,  ClassroomRepository $repo, Request $req): Response  // injection de dependance de managerRegistry
    {
        $s = new Student();  //instance de student

        $form = $this->createForm(StudentType::class, $s);  // on va creer un formulaire
        $form->handleRequest($req);  // on va recuperer les donnees du formulaire
        if ($form->isSubmitted()) {
            $em = $mr->getManager(); // on va recuperer le manager
            $em->persist($s);  // on va informer doctrine qu'on va ajouter s
            $em->flush();  // on va executer la requete
        }

        return $this->render('student/add.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(ManagerRegistry $mr, StudentRepository $repo, $id)
    {
        $s = $repo->find($id);  // on va chercher l'etudiant par son id

        if ($s) {
            $em = $mr->getManager();  // on va recuperer le manager
            $em->remove($s);  // on va informer doctrine qu'on va supprimer s
            $em->flush();  // on va executer la requete
            return $this->redirectToRoute('fetch');  // on va rediriger vers la route fetch
        }
        return new Response('Etudiant non trouvé');
    }


    #[Route('/update/{id}', name: 'update')]
    public function update(ManagerRegistry $mr, StudentRepository $repo, $id)
    {
        $s = $repo->find($id);  // on va chercher l'etudiant par son id

        if ($s) {
            $s->setName("nouveau nom");
            $em = $mr->getManager();  // on va recuperer le manager
            $em->persist($s);  // on va informer doctrine qu'on va supprimer s
            $em->flush();  // on va executer la requete
            return $this->redirectToRoute('fetch');  // on va rediriger vers la route fetch
        }
        return new Response('Etudiant non trouvé');
    }


    #[Route('/dql', name: 'dql')]
    public function dql(EntityManagerInterface $em): Response  // 1- injecter le service EntityManagerInterface
    {
        // $result=$studentRepository->findAll();
        $req = $em->createQuery('select s from App\Entity\Student s ');
        $result = $req->getResult();


        return $this->render('student/dql.html.twig', [
            'students' => $result,
        ]);
    }

    #[Route('/dql2', name: 'dql2')]
    public function dql2(EntityManagerInterface $em): Response  // 1- injecter le service EntityManagerInterface
    {
        // $result=$studentRepository->findAll();
        $req = $em->createQuery('select s.name from App\Entity\Student s order by s.name ASC');
        $result = $req->getResult();

        dd($result);
    }

    #[Route('/dql3', name: 'dql3')]
    public function dql3(EntityManagerInterface $em): Response  // 1- injecter le service EntityManagerInterface
    {
        // $result=$studentRepository->findAll();
        $req = $em->createQuery('select count(s) from App\Entity\Student s ');
        $result = $req->getResult();

        dd($result);
    }

    #[Route('/dql4', name: 'dql4')]
    public function dql4(Request $request, StudentRepository $repo): Response
    {
        $result = $repo->findAll();

        if ($request->isMethod('post')) {
            $value = $request->get('nom');
            $result = $repo->fetchStudentsByName($value);
        }
        return $this->render('student/dql.html.twig', [
            'students' => $result,
        ]);
    }

    #[Route('/dqljoin', name: 'dqljoin')]
    public function dqljoin(Request $request, StudentRepository $repo): Response
    {

        $result = $repo->fetchStudentsAffected();

        return $this->render('student/dql.html.twig', [
            'students' => $result,
        ]);
    }

    #[Route('/qb', name: 'qb')]
    public function qb(Request $request, StudentRepository $repo): Response
    {

        $result = $repo->fetchStudentsqb();
        dd($result);
        return $this->render('student/dql.html.twig', [
            'students' => $result,
        ]);
    }



}
