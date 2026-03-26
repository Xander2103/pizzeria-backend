<?php
//autcontroller.php
//nodig voor inloggen, uitloggen en sessies bijhouden
declare(strict_types=1);

namespace App\Controller;

use App\Service\AuthService;
use App\Service\SessionService;
use Twig\Environment;

class AuthController
{
    public function login(Environment $twig): void
    {
        $session = new SessionService();

        // GET -> toon login pagina
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $error = (string)$session->get('auth_error', '');
            $session->remove('auth_error');

            $lastEmail = (string)($_COOKIE['last_email'] ?? '');

            echo $twig->render('login.twig', [
                'error' => $error,
                'last_email' => $lastEmail,
            ]);
            return;
        }

        // POST -> verwerken
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $auth = new AuthService();

        // ✅ Belangrijk: login() geeft customerId terug (0/null bij fout)
        $customerId = $auth->login($email, $password);

        if ($customerId !== null && $customerId > 0) {
            // ✅ ingelogd blijven: customer_id in session
            $session->set('customer_id', $customerId);

            // onthoud laatste email (30 dagen)
            setcookie('last_email', $email, time() + 60 * 60 * 24 * 30, '/');

            header('Location: ?page=products');
            exit;
        }

        $session->set('auth_error', 'Onjuiste login gegevens.');
        header('Location: ?page=login');
        exit;
    }

    public function logout(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ?page=products');
            exit;
        }

        $session = new SessionService();
        $session->remove('customer_id');
        $session->remove('guest_customer');

        header('Location: ?page=products');
        exit;
    }
    public function showLogin(\Twig\Environment $twig): void
    {
        echo $twig->render('login.twig', [
            'error' => '',
            'last_email' => (string)($_COOKIE['last_email'] ?? ''),
        ]);
    }
}
