<?php
session_start();
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once "$root/project/avanzada 3/Nueva carpeta/Chat/service/db.php";

$s = $db->prepare("SELECT * FROM contact");

$s->execute();
$contacts = $s->fetchAll();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">


    <title>chat app - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
    <script src="js/jquery.min.js"></script>
    <style type="text/css">
        body {
            background-color: #f4f7f6;
            margin-top: 20px;

        }

        .scroll-container {
            display: block;
            overflow-y: scroll;
            scroll-behavior: smooth;
            margin: 0 auto;
            scroll-snap-align: end;
        }

        .scroller {
            height: 400px;
            overflow-y: scroll;
            scrollbar-color: #007 #bada55;
            scrollbar-gutter: stable both-edges;

        }




        .card {
            background: #fff;
            transition: .5s;
            border: 0;
            margin-bottom: 30px;
            border-radius: .55rem;
            position: relative;
            width: 100%;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 10%);
        }

        .chat-app .people-list {
            width: 280px;
            position: absolute;
            left: 0;
            top: 0;
            padding: 20px;
            z-index: 7
        }

        .chat-app .chat {
            margin-left: 280px;
            border-left: 1px solid #eaeaea
        }

        .people-list {
            -moz-transition: .5s;
            -o-transition: .5s;
            -webkit-transition: .5s;
            transition: .5s
        }

        .people-list .chat-list li {
            padding: 10px 15px;
            list-style: none;
            border-radius: 3px
        }

        .people-list .chat-list li:hover {
            background: #efefef;
            cursor: pointer
        }

        .people-list .chat-list li.active {
            background: #efefef
        }

        .people-list .chat-list li .name {
            font-size: 15px
        }

        .people-list .chat-list img {
            width: 45px;
            border-radius: 50%
        }

        .people-list img {
            float: left;
            border-radius: 50%
        }

        .people-list .about {
            float: left;
            padding-left: 8px
        }

        .people-list .status {
            color: #999;
            font-size: 13px
        }

        .chat-history {
            min-height: 400px;
        }

        .chat .chat-header {
            padding: 15px 20px;
            border-bottom: 2px solid #f4f7f6
        }

        .chat .chat-header img {
            float: left;
            border-radius: 40px;
            width: 40px
        }

        .chat .chat-header .chat-about {
            float: left;
            padding-left: 10px
        }

        .chat .chat-history {
            padding: 20px;
            border-bottom: 2px solid #fff
        }

        .chat .chat-history ul {
            padding: 0
        }

        .chat .chat-history ul li {
            list-style: none;
            margin-bottom: 30px
        }

        .chat .chat-history ul li:last-child {
            margin-bottom: 0px
        }

        .chat .chat-history .message-data {
            margin-bottom: 15px
        }

        .chat .chat-history .message-data img {
            border-radius: 40px;
            width: 40px
        }

        .chat .chat-history .message-data-time {
            color: #434651;
            padding-left: 6px
        }

        .chat .chat-history .message {
            color: #444;
            padding: 18px 20px;
            line-height: 26px;
            font-size: 16px;
            border-radius: 7px;
            display: inline-block;
            position: relative
        }

        .chat .chat-history .message:after {
            bottom: 100%;
            left: 7%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-bottom-color: #fff;
            border-width: 10px;
            margin-left: -10px
        }

        .chat .chat-history .my-message {
            background: #efefef
        }

        .chat .chat-history .my-message:after {
            bottom: 100%;
            left: 30px;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-bottom-color: #efefef;
            border-width: 10px;
            margin-left: -10px
        }

        .chat .chat-history .other-message {
            background: #e8f1f3;
            text-align: right
        }

        .chat .chat-history .other-message:after {
            border-bottom-color: #e8f1f3;
            left: 93%
        }

        .chat .chat-message {
            padding: 20px
        }

        .online,
        .offline,
        .me {
            margin-right: 2px;
            font-size: 8px;
            vertical-align: middle
        }

        .online {
            color: #86c541
        }

        .offline {
            color: #e47297
        }

        .me {
            color: #1d8ecd
        }

        .float-right {
            float: right
        }

        .clearfix:after {
            visibility: hidden;
            display: block;
            font-size: 0;
            content: " ";
            clear: both;
            height: 0
        }

        @media only screen and (max-width: 767px) {
            .chat-app .people-list {
                height: 465px;
                width: 100%;
                overflow-x: auto;
                background: #fff;
                left: -400px;
                display: none
            }

            .chat-app .people-list.open {
                left: 0
            }

            .chat-app .chat {
                margin: 0
            }

            .chat-app .chat .chat-header {
                border-radius: 0.55rem 0.55rem 0 0
            }

            .chat-app .chat-history {
                height: 300px;
                overflow-x: auto
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 992px) {
            .chat-app .chat-list {
                height: 650px;
                overflow-x: auto
            }

            .chat-app .chat-history {
                height: 600px;
                overflow-x: auto
            }
        }

        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape) and (-webkit-min-device-pixel-ratio: 1) {
            .chat-app .chat-list {
                height: 480px;
                overflow-x: auto
            }

            .chat-app .chat-history {
                height: calc(100vh - 350px);
                overflow-x: auto
            }
        }
    </style>
</head>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card chat-app">
                    <div id="plist" class="people-list">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Search...">
                        </div>
                        <ul class="list-unstyled chat-list mt-2 mb-0" id="contact">

                            <?php
                            foreach ($contacts as $val) {

                               

                                $s = $db->prepare("SELECT description as des FROM message WHERE message.id = (SELECT MAX(id) 
                                FROM message WHERE contact_id =:c)");

                                $s->bindValue(':c', $val['id'], PDO::PARAM_INT);

                                $s->execute();
                                $msj = $s->fetchAll();

                                if (count($msj) > 0) {
                                    $mj = $msj[0]['des'] ;
                                } else {
                                    $mj = 'Sin mensajes';
                                    
                                }

                                echo '
                                <li class="clearfix chat_usr" id="' . $val['name'] . '">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar' . $val['avatar'] . '.png" alt="avatar">
                                    <div class="about">
                                        <div class="name">' . $val['name'] . '</div>
                                        <div class="status"><i class="fa fa-circle online" id="msj-' . $val['name'] . '">   '.$mj.'</i></div>
                                    </div>
                                </li>';
                            }
                            ?>


                        </ul>
                    </div>
                    <div class="chat">
                        <div class="chat-header clearfix">
                            <div class="row">
                                <div class="col-lg-6">
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info" id="img-contact">
                                    </a>
                                    <div class="chat-about">
                                        <h6 class="m-b-0 first_name"></h6>
                                    </div>
                                </div>
                                <div class="col-lg-6 hidden-sm text-right">
                                    <a href="javascript:void(0);" class="btn btn-outline-secondary"><i class="fa fa-camera"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-primary"><i class="fa fa-image"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-info"><i class="fa fa-cogs"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-outline-warning"><i class="fa fa-question"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="chat-history scroller">

                            <div class="text-center" id="welcom">
                                <h3>Bienvenidos</h3>
                            </div>

                            <ul class="m-b-0 " id="chat">

                            </ul>
                        </div>
                        <div class="chat-message clearfix">
                            <div class="input-group mb-0">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-send"></i></span>
                                </div>
                                <input type="text" id="txt_msj" class="form-control" placeholder="Enter text here..." readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">


    </script>
    <script>
        // Configuración del cliente MQTT
        const broker = "localhost"; //test.mosquitto.org
        const port = 8083 //8081; // Puerto por defecto para WebSockets    
        const qos = 1; // Calidad de servicio (QoS)
        var name = "";
        var contacts = [];
        var clients = [];
        var ban = false;

        obtain_contact();

        function obtain_contact() {
            $.ajax({
                url: 'service/contact.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // $('#comment').html('');
                    if (response.length > 0) {
                        // console.log('contactos #: ', response);

                        $.each(response, function(index, cts) {
                            contacts.push(cts);

                            //set many clients
                            var client = new Paho.MQTT.Client(broker, port, cts.name);
                            client.onMessageArrived = onMessageArrived;
                            clients.push(client);
                            client.connect({
                                onSuccess: function() {
                                    onConnect(client)
                                },
                                useSSL: false, //usar conexión segura (SSL)
                            });

                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error al guardar los datos:', error);
                }
            });
        }

        //connect function
        onConnect = (client) => {
            console.log("Connect MQTT");
            // Suscribirse al topic
            topic = "udenar/chat/" + client.clientId;
            client.subscribe(topic, {
                qos: qos
            });
            // console.log("suscribe to: " + topic);
        };

        //message arrive
        onMessageArrived = (message) => {
            console.log("Mensaje recibido en " + message.destinationName + ": " + message.payloadString);
            client_name = message.destinationName.split("/")[2];
            console.log('name: ', name, ' cliente: ', client_name);
            showMessage(message.payloadString, client_name);

            if (name != "" && client_name == name) {
                if (ban === false) {
                    saveMessage(message.payloadString, 'r')
                }
            } else {
                if (name == "") {
                    name = client_name;
                }

                saveMessage(message.payloadString, 'r')
            }

            ban = false;
        };

        function showMessage(message, client_name) {

            if (name != "" && client_name == name) {

                console.log('cliente: ', client_name);


                html = document.querySelector('#chat');
                li = document.createElement('li');
                li.classList.add('clearfix');

                const ahora = new Date();

                const año = ahora.getFullYear();
                const mes = ahora.getMonth() + 1;
                const dia = ahora.getDate();

                const fecha = `${año}-${mes < 10 ? '0' : ''}${mes}-${dia < 10 ? '0' : ''}${dia}`;

                hr = ahora.getHours();
                tm = ahora.getMinutes();


                const horas = hr < 10 ? `0${hr}` : hr;
                const minutos = tm < 10 ? `0${tm}` : tm;
                console.log(' hora: ', horas, ' min: ', minutos, ' date: ', fecha);

                var img;
                contacts.map(function(currentValue, index, cts) {
                    if (currentValue.name === name) {
                        img = currentValue.avatar;
                    }
                });

                if (ban === true) {
                    li.innerHTML = `
                    <div class="message-data">
                        <span class="message-data-time" style="font-size:12px">${horas}:${minutos}, ${fecha}</span>
                    </div>
                    <div class="message my-message">${message}</div>`;

                    $("#txt_msj").val('');

                } else {
                    li.innerHTML = `
                    <li class="clearfix">
                        <div class="message-data text-right">
                        <span class="message-data-time" style="font-size:12px">${horas}:${minutos},${fecha}</span>
                        <img src="https://bootdey.com/img/Content/avatar/avatar${img}.png" alt="avatar">
                        </div>
                        <div class="message other-message float-right">${message}</div>
                    </li>
                    `;
                }
                html.appendChild(li);
            }

            msj = message.length < 10 ? message : message.slice(0, 15);
            $("#msj-" + client_name).html("  " + msj + '...');
        }

        function saveMessage(message, type) {
            var id;
            contacts.map(function(currentValue, index, cts) {
                if (currentValue.name === name) {
                    id = currentValue.id;
                }
            });

            $.ajax({
                url: 'service/save_msj.php',
                method: 'POST',
                data: {
                    description: message,
                    id: id,
                    type: type
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        console.log(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error al guardar los datos:', error);
                }
            });
        }



        //events
        $(function() {
            //Change chat 
            $(".chat_usr").click(function() {
                console.log($(this).attr('id'));
                name = $(this).attr('id');
                $(".first_name").html('<h6 class="m-b-0 first_name">' + name + '</h6> <small>Last seen: 2 hours ago</small>');
                $("#lbl_chat").html("<b>" + name + "</b>");
                $("#chat_msj").html("");
                $("#txt_msj").val("");

                $("#welcom").html("");
                document.querySelector("#txt_msj").readOnly = false;

                var id;
                var ig;
                contacts.map(function(currentValue, index, cts) {
                    if (currentValue.name === name) {
                        id = currentValue.id;
                        ig = currentValue.avatar;
                    }
                });

                $("#img-contact").html("");
                html = document.querySelector("#img-contact");
                img = document.createElement('img');
                img.setAttribute('src', 'https://bootdey.com/img/Content/avatar/avatar' + ig + '.png');
                html.appendChild(img);

                $.ajax({
                    url: 'service/show_chat.php',
                    method: 'GET',
                    data: {
                        id: id,
                    },
                    dataType: 'json',
                    success: function(response) {
                        $("#chat").html("");
                        if (response.length > 0) {
                            // console.log(response);
                            $("#chat").html("");
                            html = document.querySelector('#chat');



                            $.each(response, function(index, msj) {
                                li = document.createElement('li');
                                li.classList.add('clearfix');

                                if (msj.type === 's') {

                                    li.innerHTML = `
                                    <div class="message-data">
                                        <span class="message-data-time" style="font-size:12px">${msj.time}, ${msj.date}</span>
                                    </div>
                                    <div class="message my-message">${msj.description}</div>`;

                                } else {
                                    li.innerHTML = `
                                    <li class="clearfix">
                                        <div class="message-data text-right">
                                            <span class="message-data-time" style="font-size:12px">${msj.time}, ${msj.date}</span>
                                            <img src="https://bootdey.com/img/Content/avatar/avatar${ig}.png" alt="avatar">
                                        </div>
                                        <div class="message other-message float-right">${msj.description}</div>
                                    </li>
                                    `;
                                }

                                html.appendChild(li);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al cargar los datos:', error);
                    }

                });

            });

            //send message
            $(".fa-send").click(function() {
                msj = $("#txt_msj").val();
                if (msj != '') {
                    ban = true;

                    var i;
                    contacts.map(function(currentValue, index, cts) {
                        if (currentValue.name === name) {
                            i = index;
                        }
                    });

                    client = clients[i];
                    console.log("sending: " + msj);

                    const message = new Paho.MQTT.Message(msj);
                    message.destinationName = "udenar/chat/" + name;
                    message.qos = qos;
                    client.send(message);
                    console.log("message publish to: " + message.destinationName);
                    $("#txt_msj").val('');

                    saveMessage(message.payloadString, 's');
                }
            });

        });
    </script>
</body>

</html>

<!-- <li class="clearfix active">
                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                <div class="about">
                                    <div class="name">Pablo</div>
                                    <div class="status"> <i class="fa fa-circle online"></i> online </div>
                                </div>
                            </li>
                            <li class="clearfix active">
                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
                                <div class="about">
                                    <div class="name">Valverde</div>
                                    <div class="status"> <i class="fa fa-circle online"></i> online </div>
                                </div>
                            </li>
                            <li class="clearfix">
                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">
                                <div class="about">
                                    <div class="name">Mike Thomas</div>
                                    <div class="status"> <i class="fa fa-circle online"></i> online </div>
                                </div>
                            </li>
                            <li class="clearfix">
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">
                                <div class="about">
                                    <div class="name">Jorge Rivera</div>
                                    <div class="status"> <i class="fa fa-circle offline"></i> left 10 hours ago </div>
                                </div>
                            </li>
                            <li class="clearfix">
                                <img src="https://bootdey.com/img/Content/avatar/avatar8.png" alt="avatar">
                                <div class="about">
                                    <div class="name">Monica Ward</div>
                                    <div class="status"> <i class="fa fa-circle online"></i> online </div>
                                </div>
                            </li>
                            <li class="clearfix">
                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">
                                <div class="about">
                                    <div class="name">Dean Henry</div>
                                    <div class="status"> <i class="fa fa-circle offline"></i> offline since Oct 28 </div>
                                </div>
                            </li>

                            // html = $("#my-message").html();
                // html += "<p>" + message.payloadString + "</p>";
                // $(".my-message").html(html)
                 -->