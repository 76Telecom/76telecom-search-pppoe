<?php

function criarUsario($dados)
{
    $open = new View();

    $login_one = $open->Web(
        CONFIG['ERVIN'] . '/login.php?log=1',
        'username=' . CONFIG['USERNAME'] . '&password=' . CONFIG['PASSWORD'] . '&login_csr=Login',
        $open->Header(),
        true
    );

    preg_match('/Set-Cookie: (.*)\b/', $login_one, $Cookies);

    $open->Web(
        CONFIG['ERVIN'] . '/radius_grava_user.php',
        http_build_query([
            "id" => 0,
            "user" => $dados['pppoe'],
            "site" => '76telecom',
            "password" => $dados['password'],
            "enderecoip" => '',
            "banda_up" => $dados['upload'],
            "banda_down" => $dados['download'],
            "pool_corporativo" => 0
        ]),
        [
            'Cookie: ' . $Cookies[1] . '; _ga=GA1.3.1549464311.1574429943; _gid=GA1.3.561873516.1574429943; _gat=1',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36'
        ]
    );

    $open->Clear(true, 'ervincache.txt');

    return true;
}