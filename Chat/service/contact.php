<?php

session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/project/avanzada 3/Nueva carpeta/Chat/service/db.php";

$s = $db->prepare('SELECT * FROM contact');
// -- JOIN public.message ON contact.id = message.contact_id
// -- ORDER BY message.date,time ASC

$s->execute();
$contact = $s->fetchAll();

echo json_encode($contact);

?>