<?php
declare(strict_types=1);

const RECIPIENT_EMAIL = 'iandeveloper0412@gmail.com';

function redirectToPortfolio(string $status): void
{
    header('Location: index.html?contact=' . rawurlencode($status) . '#contact-section', true, 303);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToPortfolio('invalid');
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$subject = trim((string) ($_POST['subject'] ?? 'Project inquiry'));
$message = trim((string) ($_POST['message'] ?? ''));
$website = trim((string) ($_POST['website'] ?? ''));

if ($website !== '') {
    redirectToPortfolio('sent');
}

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectToPortfolio('invalid');
}

$name = preg_replace('/[\r\n]+/', ' ', $name) ?? '';
$subject = preg_replace('/[\r\n]+/', ' ', $subject) ?? 'Project inquiry';
$subject = mb_substr($subject, 0, 140);
$message = mb_substr($message, 0, 5000);

$mailSubject = 'Portfolio inquiry: ' . $subject;
$mailBody = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}\n";
$headers = [
    'From: Portfolio Contact Form <no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '>',
    'Reply-To: ' . $email,
    'Content-Type: text/plain; charset=UTF-8',
];

$sent = mail(RECIPIENT_EMAIL, $mailSubject, $mailBody, implode("\r\n", $headers));
redirectToPortfolio($sent ? 'sent' : 'error');
