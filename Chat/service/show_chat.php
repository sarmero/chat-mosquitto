<?php

session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/project/avanzada 3/Nueva carpeta/Chat/service/db.php";

$id = $_GET["id"];

$s = $db->prepare('SELECT message.* FROM message
JOIN contact ON contact.id = message.contact_id and contact.id = :c
ORDER BY date, time ASC');

$s->bindValue(':c', $id, PDO::PARAM_INT);

$s->execute();
$chat = $s->fetchAll();

echo json_encode($chat);

?>