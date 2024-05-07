<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\VerifPasswordType;
use App\Form\VerificationCodeType;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;

class LoginController extends AbstractController
{
    private $entityManager;
    private $verificationCode;
    private $user;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request, UserRepository $userRepository, SessionInterface $session): Response
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle login form submission
            $userFormData = $form->getData();
            $user = $userRepository->findOneBy(['email' => $userFormData->getEmail()]);

            if ($user !== null && password_verify($userFormData->getPassword(), $user->getPassword())) {
                // Start session and store user data
                $session->set('user', $user); // Store user entity in session
                $session->set('id', $user->getId());
                $session->set('username', $user->getUsername());
                $session->set('email', $user->getEmail());
                $session->set('role', $user->getRole());
                $session->set('isBanned', $user->getIsBanned());
                return $this->redirectToRoute('app_home');
            } else {
                // Handle invalid login
                $this->addFlash('error', 'Invalid email or password.');
            }
        }

        return $this->render('login/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->invalidate();
        return $this->redirectToRoute('login');
    }

    #[Route('/forgetPasswordEmail', name: 'app_forgetPasswordEmail')]
    public function forgetPasswordEmail(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $user = $session->get('user');
        $otp = bin2hex(random_bytes(8));
        $user->setOtp($otp); // Assuming 'otp' is the name of the field in your User entity

        // Persist the changes to the database
        $entityManager->persist($user);
        $entityManager->flush();

        $session->set('forgetPasswordCode', $otp);
        $transport = Transport::fromDsn('smtp://badiiboussaidii@gmail.com:yajjdesfikptvkqz@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('badiiboussaidii@gmail.com')
            ->to($user->getEmail())
            ->subject('Email Verification')
            ->html($this->renderView(
                'emails/signup.html.twig',
                ['username' => $user->getUsername(), 'verificationCode' => $otp]
            ));

        $mailer->send($email);
        return $this->redirectToRoute('app_verifyPasswordCode');
    }



    #[Route('/verifyPasswordCode', name: 'app_verifyPasswordCode')]
    public function verifyPasswordCode(Request $request, SessionInterface $session, UserRepository $userRepo): Response
    {
        // Retrieve the verification code stored in the session
        $verificationCode = $session->get('forgetPasswordCode');

        $form = $this->createForm(VerificationCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $verifCode = $formData['Code'];

            // Fetch the user entity from the session
            $user = $session->get('user');

            if ($user !== null) {
                $user = $userRepo->findOneBy(['email' => $user->getEmail()]);

                // Compare the verification code with the one stored in the session
                if ($verifCode === $verificationCode) {
                    return $this->redirectToRoute('app_ForgetPassword');
                } else {
                    // Verification failed
                    return new Response("Verification Failed");
                }
            } else {
                // User entity is null in the session
                // Handle this scenario, e.g., redirect to login page or display an error message
                return new Response("User not found in session");
            }
        }

        return $this->render('emails/Verif.html.twig', [
            'VerifFrom' => $form->createView(),
            'Label' => "Please enter your Verification code"
        ]);
    }



    #[Route('/claimEmail', name: 'app_claimEmail')]
    public function claimEmail(Request $request, SessionInterface $session, UserRepository $userRepo): Response
    {

        $form = $this->createForm(VerificationCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $verifCode = $formData['Code'];

            // Fetch the user entity from the session
            $user = $userRepo->findOneBy(['email' => $verifCode]);
            $session->set('user', $user);
            return $this->redirectToRoute('app_forgetPasswordEmail');
        }

        return $this->render('emails/Verif.html.twig', [
            'VerifFrom' => $form->createView(),
            'Label' => "Please enter your e-mail"
        ]);
    }



    #[Route('/ForgetPassword', name: 'app_ForgetPassword')]
    public function ForgetPassword(Request $request, SessionInterface $session, UserRepository $userRepo): Response
    {
        $form = $this->createForm(VerifPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $verifCode = $formData['Code'];

            // Fetch the user entity from the session
            $user = $session->get('user');
            $user = $userRepo->findOneBy(['email' => $user->getEmail()]);
            $user->setPassword(password_hash($verifCode, PASSWORD_DEFAULT));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('emails/Verif.html.twig', [
            'VerifFrom' => $form->createView(),
            'Label' => "password"
        ]);
    }
}
