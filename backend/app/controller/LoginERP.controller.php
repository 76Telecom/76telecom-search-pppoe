<?php

function loginErp($login)
{
    $open = new View();

    $open->Verify($login);

    $login_one = $open->Web(
        CONFIG['ERP'] . "/conectar",
        "usuario={$login['user']}&senha={$login['pass']}",
        $open->Header(),
        true
    );

    if (preg_match_all('/(Usuário ou senha inválidos)/i', $login_one)) {
        die(json_encode(array('status' => false, 'return' => 'Usuário ou senha inválidos, favor revisar.')));
    }

    preg_match('/Set-Cookie: (.*)\b/', $login_one, $Cookies);

    // $open->Save($user, $Cookies[1]);

    return $Cookies[1];
}