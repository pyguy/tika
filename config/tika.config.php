<?php

return [

    "tika" => [
        "version" => "2.5",
        "website" => "http://tika.ir"
    ],

    "ping_hosts" => [
        "google.com"
    ],

    "traceroute_hosts" => [
        "google.com"
    ],

    "services" => [
        
        "show_port" => true,

        "list" => [

            [
                "name" => "SSH",
                "host" => "localhost",
                "port" => 22,
                "protocol" => "tcp"
            ],

            [
                "name" => "PPPOE",
                "host" => "localhost",
                "port" => 22,
                "protocol" => "tcp"
            ],

            [
                "name" => "DNS",
                "host" => getDNShost(),
                "port" => 22,
                "protocol" => "tcp"
            ]

        ]

    ]

];