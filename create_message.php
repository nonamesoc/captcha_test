<?php

require_once 'src/Connection.php';

session_start();

if (empty($_POST["submit"])) {
    header('Location: ' . $_SERVER['HTTP_REFERER'], true, 301);
}

$title = $_POST["title"];
$message = $_POST["message"];
$captcha_code = $_POST["captcha_code"];
$errors = [];
$correct_code = $_SESSION['captcha_code'];
if (empty($captcha_code)) {
    $errors['captcha_code'] = 'Введите капчу';
}
elseif ($captcha_code !== $correct_code) {
    $errors['captcha_code'] = 'Неверный ответ';
}

foreach (['title' => $title, 'message' => $message] as $key => $value) {
    if (empty($value)) {
        $errors[$key] = 'Заполните поле';
    }
}

if (strlen($title) > 255) {
    $errors['title'] = 'Превышение длины, максимум 255 символов';
}

if (!empty($errors)) {
    $_SESSION['form']['values'] = [
      'title' => $title,
      'message' => $message,
    ];
    $_SESSION['form']['errors'] = $errors;
}
else {
    try {
        $db = new Connection();
        $db->insert('messages', [
          'title' => $title,
          'text' => $message,
        ]);

        $_SESSION['notice'] = 'Сообщение отправлено';
    }
    catch (Throwable $exception) {
        http_response_code(500);
        exit("Не удалось записать сообщение: " . $exception->getMessage());
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER'], true, 301);
