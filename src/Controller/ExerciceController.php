<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciceController extends AbstractController
{
    #[Route('/exercice/{username}', name: 'app_exercice')]
    public function index($username): Response
    {
        $name="AMir";
        return $this->render('exercice/index.html.twig', [
            'userName' => $username,
        ]);
    }
    

    #[Route('/user', name: 'user')]
    public function listUser(): Response
    {
        $user= array(
        array('id' => 1 ,'name'=>'Amir','age'=>25,'image'=>'https://www.w3schools.com/w3images/avatar2.png'),
        array('id' => 2 ,'name'=>'ahmed','age'=>35,'image'=>'https://www.w3schools.com/w3images/avatar2.png'),
        array('id' => 3 ,'name'=>'mohamed','age'=>45,'image'=>'https://www.w3schools.com/w3images/avatar2.png'),
        );
        return $this->render('exercice/user.html.twig', [
            'users' => $user,
        ]);
    }

    #[Route('/detail/{id}', name: 'd')]
    public function detail($id): Response
    {
        return $this->render('exercice/detail.html.twig', [
            'id' => $id,
        ]);
    }
}
