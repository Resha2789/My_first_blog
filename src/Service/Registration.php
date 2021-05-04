<?php

namespace App\Service;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class Registration extends AbstractController
{
    protected $request;

    private $passwordEncoder;

    public function __construct(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->request = $request;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function registration()
    {

        $request = $this->request->getRequest();

        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $this->container->get('session')->set('_security_main', serialize($token));

            $this->addFlash(
                'success',
                'Вы зарегистрировались'
            );

            return true;
        }
        return false;
    }

    public function registration_form()
    {
        $request = $this->request->getRequest();

        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        return $form;
    }
}