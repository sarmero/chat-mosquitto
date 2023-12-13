<?php

session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/project/avanzada 3/Nueva carpeta/Chat/service/db.php";

$des = $_POST['description'];
$id = $_POST['id'];
$tp = $_POST['type'];

$s = $db->prepare('INSERT INTO message(contact_id, description, type) 
VALUES ( :c, :d, :t)');

$s->bindValue(':c', $id, PDO::PARAM_INT);
$s->bindValue(':d', $des, PDO::PARAM_STR);
$s->bindValue(':t', $tp,  PDO::PARAM_STR);



try {
    $s->execute();
    // $lr = $s->fetchAll();
    $response['success'] = true;
    $response['message'] = 'Los datos se han guardado correctamente';

} catch (Exception $e) {
    echo "Error";
    $response['success'] = false;
    $response['message'] = 'Solicitud inválida';
    
}

echo json_encode($response);

?>