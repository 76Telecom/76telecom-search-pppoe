$(function(){
    if (getConfig("user") && !$("#user").val() || getConfig("pass") && !$("#pass").val()) {
        $("#user").val(getConfig("user"));
        $("#pass").val(getConfig("pass"));
    } else {
        return $("#moda_config").modal();
    }

    $("#btn_minus").click(function() {
        $("#about").toggle(0, function() {
            if ($(this).is(":visible")) {
                $("#btn_minus").html('<i class="fa fa-minus"> </i>');
            } else {
                $("#btn_minus").html('<i class="fa fa-plus"> </i>');
            }
        });
    });

    Ladda.bind("[data-plugin='ladda']", { timeout: 450 });
});


getConfig = (name) => {
  return localStorage.getItem(name);
}

setConfig = (name, value = null) => {
  if (value === null) {
      return localStorage.removeItem(name);
  } else {
      return localStorage.setItem(name, value);
  }
}

notify = (title, msg, icone) => {
    var options = { body: msg, icon: icone, dir: "ltr" };

    if (Notification.permission === "granted") 
    {
        new Notification(title, options);
    } 
    else if (Notification.permission !== "denied") 
    {
        Notification.requestPermission(function(permission) {
            if (permission === "granted") {
                new Notification(title, options);
            }
        });
    }
}

showMessage = (params) => {
    Swal.fire({
        title: params.title,
        type: params.type,

        showCloseButton: true,
        showCancelButton: false, 
        showConfirmButton: false,
        
        allowOutsideClick: false,

        html: params.context,
        footer: params.footer
    });
}

setReset = (boolean) => {
    if (boolean == true) {
        $('.fa-play').removeClass('fa fa-play').addClass('fa fa-spinner fa-spin');
    }
    else {
        $('.fa-spinner').removeClass('fa fa-spinner fa-spin').addClass('fa fa-play');
        $("#lista").val("");
    }

    $("#lista").attr("disabled", boolean);
    $("#config").attr("disabled", boolean);
    $("#iniciar").attr("disabled", boolean);
}

salvarConfig = () => {
    if (!$("#user").val() || !$("#pass").val()) {
        $("#motivoerro").html("Necessário preencher todos os campos!");
        $("#alerta_erro").show();
        Ladda.stopAll();
        return;
    }

    Ladda.stopAll();
    
    $("#alerta_erro").hide();
    $("#moda_config").modal("hide");

    setConfig("user", $("#user").val());
    setConfig("pass", $("#pass").val());
}

start = () => {
    if (!getConfig("user") || !getConfig("pass")) return $("#moda_config").modal();
    
    if (!$("#lista").val()) {
        showMessage({ 
            title: 'Atenção!', type: 'error', 
            context: '<b>Erro</b>: Necessário preencher chamado.<br />',
            footer: 'Qualquer dúvida, procure por @Caio Agiani' 
        });

        return;
    };
    
    setReset(true);

    const array = lista.value.split("\n");
    const login = array[0];

    $.ajax({
        url: "../backend/",
        type: "GET",
        data: {
            chamado: login,
            user: getConfig("user"),
            pass: getConfig("pass")
        },
        success: (data) => {
            const json = $.parseJSON(data);

            if (json.status == true) {
                const { cliente, pppoe, password, download, upload} = json.dados;
                
                showMessage({ 
                    title: `Chamado: ${login}`, type: 'success', 
                    context: `<b>${cliente}</b><br /><br />
                    <b>PPoE</b>: ${pppoe}<br />
                    <b>SENHA</b>: ${password}<br />
                    <b>DOWNLOAD</b>: ${download} Mbps<br />
                    <b>UPLOAD</b>: ${upload} Mbps<br />`,
                    footer: `<center>${json.return}</center>`
                });

                notify( "76Telecom:", "Olá " + getConfig("user") + ", \r\nPPoE gerado com sucesso.", "libs/image/fav.png" );
            } 
            else if (json.status == false) {
                showMessage({ 
                    title: `Atenção!`, type: 'error', 
                    context: `<b>Erro</b>: ${json.return}<br />`,
                    footer: 'Qualquer dúvida, procure por @Caio Agiani'
                });
            }

            setReset(false);
        }
    });
}