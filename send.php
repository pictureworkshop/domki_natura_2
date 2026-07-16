<?php
declare(strict_types=1);

// Formularz kontaktowy — wysyłka bez zewnętrznych usług (Formspree itp.).
// Wymaga hostingu z obsługą PHP i działającą funkcją mail() (typowe na hostingach współdzielonych w Polsce).

$to        = 'maciejszalaj@gmail.com'; // TYMCZASOWO na testy — przed uruchomieniem produkcyjnym zmień z powrotem na biuro@domkinatura.pl
$fromEmail = 'formularz@domkinatura.pl'; // adres z domeny strony — część serwerów pocztowych odrzuca maile z "From" spoza własnej domeny

function respond(bool $success, string $message): void
{
    $wantsJson = isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');

    if ($wantsJson) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($success ? 200 : 400);
        echo json_encode(['success' => $success, 'message' => $message]);
        exit;
    }

    // Awaryjnie (np. JS wyłączony) — wracamy na stronę z informacją w adresie URL.
    $status = $success ? 'ok' : 'error';
    header('Location: index.html?status=' . $status . '#kontakt');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    respond(false, 'Nieprawidłowa metoda żądania.');
}

// Honeypot — pole niewidoczne dla ludzi. Jeśli wypełnione, to bot: udajemy sukces i kończymy bez wysyłki.
if (!empty($_POST['_gotcha'])) {
    respond(true, 'OK');
}

function clean(string $value): string
{
    return preg_replace('/[\r\n]+/', ' ', trim($value));
}

$name    = clean($_POST['name'] ?? '');
$email   = clean($_POST['email'] ?? '');
$temat   = clean($_POST['temat'] ?? '');
$message = trim($_POST['message'] ?? '');

$missing = [];
if ($name === '')    $missing[] = 'imię i nazwisko';
if ($temat === '')   $missing[] = 'temat zapytania';
if ($message === '') $missing[] = 'wiadomość';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $missing[] = 'poprawny adres e-mail';
}

if (!empty($missing)) {
    respond(false, 'Uzupełnij: ' . implode(', ', $missing) . '.');
}

$subject = 'Nowe zapytanie ze strony Domki Natura — ' . $temat;

$body = "Nowe zapytanie ze strony domkinatura.pl\n\n"
      . "Imię i nazwisko: {$name}\n"
      . "E-mail: {$email}\n"
      . "Temat: {$temat}\n\n"
      . "Wiadomość:\n{$message}\n";

$headers = [
    'From: Formularz Domki Natura <' . $fromEmail . '>',
    'Reply-To: ' . $name . ' <' . $email . '>',
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion(),
];

$encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
$sent = mail($to, $encodedSubject, $body, implode("\r\n", $headers));

if ($sent) {
    respond(true, 'Wiadomość została wysłana. Odezwiemy się najszybciej, jak to możliwe.');
}

respond(false, 'Nie udało się wysłać wiadomości. Spróbuj ponownie później albo napisz na biuro@domkinatura.pl.');
