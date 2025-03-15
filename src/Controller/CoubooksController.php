<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Entity\Greeter;
use App\Entity\Reservation;
use App\Entity\Student;
use App\Repository\BookRepository;
use App\Repository\CourseRepository;
use App\Repository\FeedbackRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoubooksController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(FeedbackRepository $feedbackRepository): Response {
        $feedbackList = $feedbackRepository->findAll();
        $greeting = (new Greeter)->getGreeting();
        return $this->render('index.html.twig',
            [
                'greeting' => $greeting,
                'feedback_list' => $feedbackList
            ]
        );
    }

    #[Route('/courses', name: 'courses')]
    public function courses(CourseRepository $courseRepository, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('fase', ChoiceType::class, [
                'choices' => [
                    'First bachelor' => 1,
                    'Second bachelor' => 2,
                    'Third bachelor' => 3,
                    'Master' => 4
                ]
            ])->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fase =  $form['fase']->getData();
            return  $this->render('courses.html.twig',
                [
                    'courses_list' => $courseRepository->findBy(['fase' => $fase]),
                    'form' => $form
                ]
            );
        }
        return $this->render('courses.html.twig',
            [
                'courses_list' => $courseRepository->findAll(),
                'form' => $form
                ]
        );
    }

    #[Route('/feedback', name: 'feedback')]
    public function feedback(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder(new Feedback())
            ->add('author', TextType::class, ['attr' => ['id' => 'author']])
            ->add('text', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $feedback = $form->getData();
            $feedback->setCreated(new \DateTime());
            $entityManager->persist($feedback);
            $entityManager->flush();
            return $this->redirectToRoute('home', ['id' => $feedback->getId()]);
        }

        return $this->render('feedback.html.twig',
            [
                'form' => $form
            ]
        );
    }

    #[Route('/reservation', name: 'reservation')]
    public function reservation(Request $request, BookRepository $bookRepository, EntityManagerInterface $entityManager, StudentRepository $studentRepository): Response
    {
        $session = $request->getSession();
        $step = $session->get('step', 1);

        if ($step == 1) {
            $form = $this->createFormBuilder()
                ->add('fase', ChoiceType::class, [
                    'choices' => [
                        'First bachelor' => 1,
                        'Second bachelor' => 2,
                        'Third bachelor' => 3,
                        'Master' => 4
                    ]
                ])
                ->add('email', EmailType::class)
                ->add('save', SubmitType::class, ['label' => 'Next'])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $fase = $form['fase']->getData();
                $email = $form['email']->getData();
                $session->set('fase', $fase);
                $session->set('email', $email);
                $session->set('step', 2);

                $books = [];
                foreach ($bookRepository->findAll() as $book) {
                    if ($book->getCourse()->getFase() == $fase) {
                        $books[] = $book;
                    }
                }
                $session->set('books', $books);

                return $this->redirectToRoute('reservation');
            }
        } elseif ($step == 2) {
            $books = $session->get('books', []);
            $form = $this->createFormBuilder()
                ->add('books', ChoiceType::class, [
                    'choices' => array_combine(
                        array_map(fn($book) => $book->getTitle(), $books),
                        $books
                    ),
                    'multiple' => true,
                    'expanded' => true,
                ])
                ->add('save', SubmitType::class, ['label' => 'Next'])
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $selectedBooks = $form['books']->getData();
                $session->set('selected_books', $selectedBooks);
                $session->set('step', 3);

                return $this->redirectToRoute('reservation');
            }
        } elseif ($step == 3) {
            $form = $this->createFormBuilder()
                ->add('confirm', SubmitType::class, ['label' => 'Confirm'])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $student = $studentRepository->findOneBy(['email' => $session->get('email')]);
                if (!$student) {
                    $student = new Student();
                    $student->setEmail($session->get('email'));
                    $entityManager->persist($student);
                }
                $reservation = new Reservation();
                $reservation->setCreated(new \DateTime());
                $reservation->setStudent($student);
                $entityManager->persist($reservation);

//                foreach ($session->get('selected_books') as $book) { doesnt work
//                    $reservationBook = new ReservationBook();
//                    $reservationBook->setReservation($reservation);
//                    $reservationBook->setBook($book);
//                    $entityManager->persist($reservationBook);
//                }

                $entityManager->flush();
                $session->clear();
                return $this->redirectToRoute('home');
            }
        }

        return $this->render('reservation.html.twig', [
            'step' => $step,
            'form' => $form
        ]);
    }


    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/privacy', name: 'privacy')]
    public function privacy(): Response
    {
        return $this->render('privacy.html.twig');
    }

    #[Route('/terms', name: 'terms')]
    public function terms(): Response
    {
        return $this->render('terms.html.twig');
    }
}