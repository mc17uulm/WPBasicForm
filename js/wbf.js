jQuery(document).ready(($) => {

    console.log("wbf initalized");

    const check_btn = $('#check_btn');
    const msg = $('#msg');
    const download = $('#download');
    let date = -1;
    let err = "";

    // initalize event listener for check_btn
    check_btn.on('click', (e) => {

        e.preventDefault();

        msg.hide();

        console.log("clicked");

        // bool if woman or not
        const woman = $('input[name=salutation]:checked').attr('id') === "frau";

        const firstname = $('#firstName').val();
        if(firstname === "")
        {
            err += "<li>Bitte geben Sie Ihren Vornamen an</li>>";
        }
        const lastname = $('#lastName').val();
        if(lastname === "")
        {
            err += "<li>Bitte geben Sie Ihren Nachnamen an</li>>";
        }
        const email = $('#email').val();
        if(email === "")
        {
            err += "<li>Bitte geben Sie eine E-Mail-Adresse an</li>>";
        }
        if(!validateEmail(email))
        {
            err += "<li>Bitte geben Sie eine gültige E-Mail-Adresse an</li>";
        }
        const customer = $('input[name=customer]:checked').attr('id') === "y_customer";
        if(date === -1)
        {
            err += "<li>Bitte geben Sie ein Datum an</li>";
        }
        const impressum = $('input[name=impressum]:checked').attr('id') === "y_impressum";
        const dsgvo = $('input[name=dsgvo]:checked').attr('id');
        const cms = $('input[name=cms]:checked').attr('id') === "y_cms";
        const important = $('input:checkbox[name=important]:checked').map((e, a) => {return $(a).attr('id')}).get();

        if(important.length < 1)
        {
            err += "<li>Bitte geben Sie mindestens einen wichtigen Grund für Ihr Webprojekt an</li>";
        }
        if(err.length > 1)
        {
            showError(msg, `Bitte korrigieren Sie folgende Fehler:<br><ul>${err}</ul>`);
            err = "";
            return;
        }

        const obj = {
            woman: woman,
            firstname: firstname,
            lastname: lastname,
            email: email,
            customer: customer,
            date: date,
            impressum: impressum,
            dsgvo: dsgvo,
            cms: cms,
            important: important
        };

        check_btn.prop("disabled",true);
        check_btn.html(`<i class="fas fa-sync fa-spin"></i> Laden ...`);
        $.ajax({
            type: "POST",
            url: wbf.ajaxurl,
            data: {
                action: 'check_data',
                data: obj
            },
            dataType: 'json'
        }).done((d, s, o) => {
            console.log(d);
            try{
                if(d["type"] === "success")
                {
                    let url = wbf.tmpurl + d["msg"];
                    download.attr('href', url);
                    download.trigger('click');
                    download.show();
                    console.log(d["other"]);
                } else{
                    showError(msg, `Leider gabe es einen Server-Fehler. Wenden Sie sich bitte an den Administrator`);
                }
            } catch(e)
            {
                showError(msg, `Leider gabe es einen Server-Fehler. Wenden Sie sich bitte an den Administrator`);
            }
        }).fail((o,s,e) => {
                showError(msg, `Leider gabe es einen Server-Fehler. Wenden Sie sich bitte an den Administrator`);
        }).always((d, t, e) => {
            check_btn.prop("disabled",false);
            check_btn.html("Checkliste generieren");
        });
    });

    // initalize datepicker
    $('#finished_til').datepicker({
        format: 'dd/mm/yyyy',
        language: 'de-DE',
        startDate: '01-01-2019'
    }).on('changeDate', (e) => {
        date = e.date.valueOf();
    });

    const validateEmail = (e) => {
        const regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex.test(e);
    };

    const showError = (obj, err) =>
    {
        $('#msg_h').text("Fehler");
        $('#msg_p').html(err);
        msg.removeClass();
        msg.addClass('alert alert-danger');
        msg.show();
    };

    const showSuccess = (obj, msg) =>
    {
        $('#msg_h').text("Erfolg");
        $('#msg_p').html(msg);
        msg.removeClass();
        msg.addClass('alert alert-success');
        msg.show();
    }

});