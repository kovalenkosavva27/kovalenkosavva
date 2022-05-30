<?php
header('Content-Type: text/html; charset=UTF-8');
$errorOutput = '';
$errors = array();
$hasErrors = FALSE;

$defaultValues = [
  'name' => 'Савва',
  'email' => 'ex@ex.ru',
  'birthday' => '2022-05-25',
  'gender' => 'M',
  'limbs' => '4',
  'biography' => 'Моя история...',
  'contract' => ''
];

$values = array();
foreach (['name', 'email', 'birthday', 'gender', 'limbs', 'biography', 'contract'] as $key) {
  $values[$key] = !array_key_exists($key . '_value', $_COOKIE) ? $defaultValues[$key] : $_COOKIE[$key . '_value'];
}
foreach (['name', 'email', 'birthday'] as $key) {
  $errors[$key] = empty($_COOKIE[$key . '_error']) ? '' : $_COOKIE[$key . '_error'];
  if ($errors[$key] != '')
    $hasErrors = TRUE;
}

$values['superpowers'] = array();
$values['superpowers']['0'] = empty($_COOKIE['superpowers_0_value']) ? '' : $_COOKIE['superpowers_0_value'];
$values['superpowers']['1'] = empty($_COOKIE['superpowers_1_value']) ? '' : $_COOKIE['superpowers_1_value'];
$values['superpowers']['2'] = empty($_COOKIE['superpowers_2_value']) ? '' : $_COOKIE['superpowers_2_value'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
    $errorOutput = 'Спасибо, результаты сохранены.<br/>';
  }

  if (!empty($errors['name'])) {
    $errorOutput .= 'Заполните имя.<br/>';
  }
  if (!empty($errors['email'])) {
    $errorOutput .= 'Заполните email.<br/>';
  }
  if (!empty($errors['birthday'])) {
    $errorOutput .= 'Заполните дату рождения.<br/>';
  }

  include('form.php');
  exit();
}

$trimmedPost = [];
foreach ($_POST as $key => $value)
	if (is_string($value))
		$trimmedPost[$key] = trim($value);
	else
		$trimmedPost[$key] = $value;

if (empty($trimmedPost['name'])) {
  $errorOutput .= 'Заполните имя.<br/>';
  $errors['name'] = TRUE;
  setcookie('name_error', 'true');
  $hasErrors = TRUE;
} else {
  setcookie('name_error', '', 10000);
  $errors['name'] = '';
}
setcookie('name_value', $trimmedPost['name'], time() + 30 * 24 * 60 * 60);
$values['name'] = $trimmedPost['name'];

if (!preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $trimmedPost['email'])) {
  $errorOutput .= 'Заполните email.<br/>';
  $errors['email'] = TRUE;
  setcookie('email_error', 'true');
  $hasErrors = TRUE;
} else {
  setcookie('email_error', '', 10000);
  $errors['email'] = '';
}
setcookie('email_value', $trimmedPost['email'], time() + 30 * 24 * 60 * 60);
$values['email'] = $trimmedPost['email'];

if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $trimmedPost['birthday'])) {
  $errorOutput .= 'Заполните дату рождения.<br/>';
  $errors['birthday'] = TRUE;
  setcookie('birthday_error', 'true');
  $hasErrors = TRUE;
} else {
  setcookie('birthday_error', '', 10000);
  $errors['birthday'] = '';
}
setcookie('birthday_value', $trimmedPost['birthday'], time() + 30 * 24 * 60 * 60);
$values['birthday'] = $trimmedPost['birthday'];

if (!preg_match('/^[MFO]$/', $trimmedPost['gender'])) {
  $errorOutput .= 'Заполните пол.<br/>';
  $errors['gender'] = TRUE;
  $hasErrors = TRUE;
}
setcookie('gender_value', $trimmedPost['gender'], time() + 30 * 24 * 60 * 60);
$values['gender'] = $trimmedPost['gender'];

if (!preg_match('/^[0-5]$/', $trimmedPost['limbs'])) {
  $errorOutput .= 'Заполните количество конечностей.<br/>';
  $errors['limbs'] = TRUE;
  $hasErrors = TRUE;
}
setcookie('limbs_value', $trimmedPost['limbs'], time() + 30 * 24 * 60 * 60);
$values['limbs'] = $trimmedPost['limbs'];

foreach (['0', '1', '2'] as $value) {
  setcookie('superpowers_' . $value . '_value', '', 10000);
  $values['superpowers'][$value] = FALSE;
}
if (array_key_exists('superpowers', $trimmedPost)) {
  foreach ($trimmedPost['superpowers'] as $value) {
    if (!preg_match('/[0-2]/', $value)) {
      $errorOutput .= 'Неверные суперспособности.<br/>';
      $errors['superpowers'] = TRUE;
      $hasErrors = TRUE;
    }
    setcookie('superpowers_' . $value . '_value', 'true', time() + 30 * 24 * 60 * 60);
    $values['superpowers'][$value] = TRUE;
  }
}
setcookie('biography_value', $trimmedPost['biography'], time() + 30 * 24 * 60 * 60);
$values['biography'] = $trimmedPost['biography'];
if (!isset($trimmedPost['contract'])) {
  $errorOutput .= 'Вы не ознакомились с контрактом.<br/>';
  $errors['contract'] = TRUE;
  $hasErrors = TRUE;
}

if ($hasErrors) {
  include('form.php');
  exit();
}

$user = 'u47432';
      $pass_db = '9904175';
    $db = new PDO('mysql:host=localhost;dbname=u47432', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

try {
  $db->beginTransaction();
  $stmt1 = $db->prepare("INSERT INTO forms SET name = ?, email = ?, birthday = ?, 
    gender = ? , limb_number = ?, biography = ?");
  $stmt1 -> execute([$trimmedPost['name'], $trimmedPost['email'], $trimmedPost['birthday'], 
    $trimmedPost['gender'], $trimmedPost['limbs'], $trimmedPost['biography']]);
  $stmt2 = $db->prepare("INSERT INTO form_ability SET form_id = ?, ability_id = ?");
  $id = $db->lastInsertId();
  foreach ($trimmedPost['superpowers'] as $s)
    $stmt2 -> execute([$id, $s]);
  $db->commit();
}
catch(PDOException $e){
  $db->rollBack();
  $errorOutput = 'Error : ' . $e->getMessage();
  include('form.php');
  exit();
}

header('Location: ?save=1');
