<?php

function interarErp($cookies, $dados)
{
    $open = new View();

    $description = "CONFIGURAÇÕES GERADAS COM SUCESSO:
    
    Cliente: " . $dados['cliente'] . "
    PPoE: " . $dados['pppoe'] . "@76telecom
    Senha: " . $dados['password'] .  "
    Download: " . $dados['download'] . " Mbps
    Upload: " . $dados['upload'] . " Mbps";

    $login_one = $open->Web(
        CONFIG['ERP'] . '/chamadocontroller/interacao',
        'id=' . $dados['id'] . '&descricao=' . urldecode($description) . '&descricaoPrivada=&status=3&registrarTempo=&registrarTempoHora=&causa=0&agendado=&agendadoHora=&slaAtendimento=0&slaConclusao=0',
        [
            'Accept: application/json; charset=utf-8',
            'Cookie: ' . $cookies,
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36'
        ]
    );

    $msg = stripos($login_one, 'Este chamado não esta') ? 'PPoE gerado, porém, não foi salvo no ERP pois você não faz parte da fila.' : 'PPoE gerado com sucesso e salvo as configurações no ERP.';

    return json_encode([
        'status' => true,
        'return' => $msg,
        'description' => $description,
        'dados' =>  [
            'cliente' => $dados['cliente'],
            'pppoe' => $dados['pppoe'] . '@76telecom',
            'password' => $dados['password'],
            'download' => $dados['download'],
            'upload' => $dados['upload']
        ]
    ]);
}