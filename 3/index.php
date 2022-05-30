<?php

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['save'])) {
      print('Спасибо, результаты сохранены.');
    }
    include('form.php');
    exit();
}

$result;

try{

    $errors = FALSE;
    if (empty($_POST['first-name'])) {
        print('Заполните имя.<br/>');
        $errors = TRUE;
    }
    if (empty($_POST['field-email'])) {
        print('Заполните почту.<br/>');
        $errors = TRUE;
    }

    if (empty($_POST['BIO'])) {
        print('Заполните биографию.<br/>');
        $errors = TRUE;
    }
    if (empty($_POST['ch'])) {
        print('Вы должны быть согласны с условиями.<br/>');
        $errors = TRUE;
    }

    if ($errors) {
        exit();
    }

    $name = $_POST['first-name'];
    $email = $_POST['field-email'];
    $dob = $_POST['field-date'];
    $sex = $_POST['radio-sex'];
    $limbs = $_POST['radio-limb'];
    $bio = $_POST['BIO'];
    $che = $_POST['ch'];
    
    $sup= implode(",",$_POST['superpower']);

    $conn = new PDO("mysql:host=localhost;dbname=u47432", 'u47432', '9904175', array(PDO::ATTR_PERSISTENT => true));

    
    $user = $conn->prepare("INSERT INTO form SET name = ?, email = ?, dob = ?, sex = ?, libs = ?, bio = ?, che = ?");
    $user -> execute([$_POST['first-name'], $_POST['field-email'], date('Y-m-d', strtotime($_POST['field-date'])), $_POST['radio-sex'], $_POST['radio-limb'], $_POST['BIO'], $_POST['ch']]);
    $id_user = $conn->lastInsertId();

    $user1 = $conn->prepare("INSERT INTO super SET id = ?, super_name = ?");
    $user1 -> execute([$id_user, $sup]);
    $result = true;
}
catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
}


if ($result) {
  echo "Информация занесена в базу данных под ID №" . $id_user;
}
?>