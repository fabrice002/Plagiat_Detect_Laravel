// const { document } = require("postcss");

// Code JavaScript pour la detection de texte
let actus = false
let data = [];
$("#detection_text").submit(function (e) {
    e.preventDefault();
    let message = document.querySelector('.Message_scan')
    let _token = $("input[name=_token]").val();
    let link_test = $("#link_test").val();
    let contenu = $("#contenu").val();
    message.innerHTML = ""
    if (actus === false) {
        actus = true
        $.ajax({
            type: "POST",
            url: link_test,
            data: {
                _token: _token,
                contenu: contenu
            },
            beforeSend: function () {
                message.innerHTML = '<li> <span class="text-danger"> Veuillez patienter un instant ... </span> </li>'
            },
            success: function (response) {
                let n = response.length;
                message.innerHTML = ""

                if (n > 0) {
                    $.each(response, function (key, item) {
                        /* console.log( response[key].links);
                        console.log(key + " " + item); */
                        $(message).append('<li> <a target="_blank" href="' + response[key].links + ' "> ' + response[key].links + '</a> : ' + response[key].resultat + ' </li>');
                    });
                } else {
                    $(message).append('<li> Aucun plagiats  </li>');
                }
                /* if(n>0){
                    for(let i=0; i<n; i++){
                        $(message).append(content);
                    }
                } */

            }
        });
        actus = false
    }

});

function compterMots(chaine) {
    // Supprimer les espaces en début et fin de la chaîne
    chaine = chaine.trim();

    // Diviser la chaîne en un tableau de mots en utilisant les espaces comme délimiteurs
    var mots = chaine.split(' ');

    // Retourner le nombre de mots
    return mots.length;
  }

function actualiseNombreMots() {
    let file_content = $('#file_content').val();
    let n=compterMots(file_content);
    let word_number=document.getElementById('word_number')
    $(word_number).html("");
    $(word_number).html(n);
}
//Search link
function search_link() {
    let message = document.querySelector('.message_doc');
    let number = $('#number').val();
    let link_search = $("#link_search").html();
    let _token = $("input[name=_token]").val();
    message.innerHTML = "";

    $.ajax({
        type: 'POST',
        url: link_search,
        data: {
            _token: _token,
            number: number
        },
        beforeSend: function () {
            let simple_mes = document.querySelector('#simple_mes');
            $(simple_mes).html("");
            $(simple_mes).html("Recherche des liens...");
            $('#ListContentModal').modal('toggle');
            $('#PatientModal').modal('toggle');
        },
        success: function (response) {
            let body_detect = document.querySelector("#body_detect");
            let title_modal = document.querySelector("#title_modal");
            $(title_modal).html("Les liens trouvés");
            body_detect.innerHTML = "";
            if (response == undefined) {
                body_detect.innerHTML = '<li> <span class="text-danger h4"> Oops une erreur est survenu veuillez ressayer </span> </li>'
            } else {
                $.each(response, function (key, data) {
                    let htmlc = '<li><a href="' + data + '">' + data + '</a></li>'
                    $(body_detect).append(htmlc);
                });

            }
            let modal_footer = document.querySelector('#modal_footer')
            html_f = '<button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>\
            <a href="javascript:void(0)" onclick="scrap_link()" class="btn btn-primary search_link" >Scrapping des liens</a>';
            $(modal_footer).html("");
            $(modal_footer).html(html_f);
            $('#PatientModal').modal('toggle')
            $('#ListContentModal').modal('toggle')
            // $('.file').val('');
        }
    });
}
//Scrap link
function scrap_link() {
    let message = document.querySelector('.message_doc');
    // let number = $('#number').html();
    let link_scrap = $("#link_scrap").html();
    let _token = $("input[name=_token]").val();
    message.innerHTML = "";

    $.ajax({
        type: 'POST',
        url: link_scrap,
        data: {
            _token: _token,
        },
        beforeSend: function () {
            let simple_mes = document.querySelector('#simple_mes');
            $(simple_mes).html("");
            $(simple_mes).html("Scrapping des liens...");
            $('#ListContentModal').modal('toggle');
            $('#PatientModal').modal('toggle');
        },
        success: function (response) {
            let body_detect = document.querySelector("#body_detect");
            let title_modal = document.querySelector("#title_modal");
            $(title_modal).html("Algorithme de cosine similraty");
            body_detect.innerHTML = "";
            if (response == undefined) {
                body_detect.innerHTML = '<li> <span class="text-danger h4"> Oops une erreur est survenu veuillez ressayer </span> </li>'
            } else {
                let htmlString = "<li> Recherche dans la Base de donnée</li>"
                $(body_detect).html(htmlString);

            }
            let modal_footer = document.querySelector('#modal_footer')
            html_f = '<button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>\
                    <a href="javascript:void(0)" onclick="searchDB()" class="btn btn-primary search_link" >Recherche dans la base de données</a>';
            $(modal_footer).html("");
            $(modal_footer).html(html_f);
            $('#PatientModal').modal('toggle')
            $('#ListContentModal').modal('toggle')
            // $('.file').val('');
        }
    });
}

function comparerAgeDecroissant(a, b) {
    return b.resultat - a.resultat;
}

function searchDB() {
    let message = document.querySelector('.message_doc');
    let file_content = $('#file_content').val();
    let _token = $("input[name=_token]").val();
    let link_db = $("#link_db").html();
    $.ajax({
        type: "POST",
        url: link_db,
        data: {
            _token: _token,
            file_content: file_content,
            seuil: 20
        },
        beforeSend: function () {
            let simple_mes = document.querySelector('#simple_mes');
            $(simple_mes).html("");
            $(simple_mes).html("Recherche dans la base de données...");
            const toggleElement = document.getElementById('ListContentModal');
            if (window.getComputedStyle(toggleElement).display !== 'none') {
                $('#ListContentModal').modal('toggle');
                // Element is visible
                console.log('Toggle is visible');
            }
            $('#PatientModal').modal('toggle');
        },
        success: function (response) {
            message.innerHTML = ""
            let n = response.length;
            if (n > 0) {

                $.each(response, function (key, item) {
                    data.push({ lien: response[key].links, resultat: response[key].resultat });
                    // $(message).append('<li> Plagiat detecté <a target="_blank" href="' + response[key].links + ' "> ' + response[key].links + '</a> avec un taux de similarité de <span class="text-primary">'  + response[key].resultat + '</span> </li>');
                });
                function comparerAgeDecroissant(a, b) {
                    return b.resultat - a.resultat;
                }
                data.sort(comparerAgeDecroissant);
                data.forEach(function (element) {
                    $(message).append('<li> Plagiat detecté <a target="_blank" href="' + element.lien + ' "> ' + element.lien + '</a> avec un taux de similarité de <span class="text-primary">' + element.resultat + '</span> </li>');
                });
            } else {
                $(message).append('<li> Aucun plagiats  </li>');
            }
            $('#PatientModal').modal('toggle');
        }
    });

}

//Code javascript pour la detection de document
let actus_doc = false
    $("#textPlagairForm").submit(function (e) {
        e.preventDefault();
        //extract text from the document
        $('.submits').on('click', function () {
            let message = document.querySelector('.message_doc')
            var file_data = $('.file').prop('files')[0];
            let link_file = $("#link_file").html();
            let _token = $("input[name=_token]").val();
            message.innerHTML = ""
            if (file_data != undefined) {
                var form_data = new FormData();
                form_data.append('file', file_data);
                form_data.append('_token', _token);
                if (actus_doc === false) {
                    actus_doc = true

                    $.ajax({
                        type: 'POST',
                        url: link_file,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        beforeSend: function () {
                            let simple_mes = document.querySelector('#simple_mes');
                            $(simple_mes).html("");
                            $(simple_mes).html("Chargement du document...");
                            $('#PatientModal').modal('toggle');
                        },
                        success: function (response) {
                            let zone_text = document.querySelector('#zone_text');
                            message.innerHTML = "";


                            let n = response.length;
                            if (response.text == undefined) {
                                message.innerHTML = '<li> <span class="text-danger h4"> Type de fichier non pris en charge(Seulement docx, pdf et txt) </span> </li>'
                            } else {
                                let zone_file=document.getElementById('zone_file');
                                zone_file.classList.add('d-none');
                                zone_text.classList.remove('d-none');
                                let file_content=document.getElementById('file_content');
                                file_content.innerHTML=response.text;
                                let word_number=document.getElementById('word_number');
                                word_number.innerHTML="Nombre de Mot="+compterMots(response.text)
                                let btn_load = document.querySelector('#btn_load')
                                let htmlbtnOn = '<button class="valid submit_o btn btn-primary">Détection en ligne</button>'
                                let htmlbtnLo = '<button class="valid submit_l btn btn-secondary">Détection en locale</button>'
                                btn_load.innerHTML = ""
                                $(btn_load).html(htmlbtnOn + '&ensp; &ensp;' + htmlbtnLo);
                            }

                            // $('.file').val('');
                            $('#PatientModal').modal('toggle')
                        }
                    });
                    actus_doc = false
                }
            }
        });

        // Search online
        $('.submit_o').on('click', function () {
            let message = document.querySelector('.message_doc');
            let file_content = $('#file_content').val();

            let link_selected = $("#link_selected").html();
            let _token = $("input[name=_token]").val();
            message.innerHTML = "";

            $.ajax({
                type: 'POST',
                url: link_selected,
                data: {
                    _token: _token,
                    file_content: file_content
                },
                beforeSend: function () {
                    let simple_mes = document.querySelector('#simple_mes');
                    $(simple_mes).html("");
                    $(simple_mes).html("Sélection des phrases...");
                    $('#PatientModal').modal('toggle');
                },
                success: function (response) {
                    let body_detect = document.querySelector("#body_detect");
                    let title_modal = document.querySelector("#title_modal");
                    $(title_modal).html("Phrases sélectionnées");
                    body_detect.innerHTML = "";
                    if (response == undefined) {
                        body_detect.innerHTML = '<li> <span class="text-danger h4"> Oops une erreur est survenu veuillez ressayer </span> </li>'
                    } else {
                        $.each(response, function (key, data) {
                            let htmlc = '<li>' + data + '</li>'
                            $(body_detect).append(htmlc);
                        });

                    }
                    let modal_footer = document.querySelector('#modal_footer')
                    html_f = '<label for="number"> Nombre maximum de liens : </label>\
                    <input type="number" required min=5 class="form-control" name="number" id="number" value="150"><br>\
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>\
                    <a href="javascript:void(0)" onclick="search_link()" class="btn btn-primary search_link" >Recherche des liens</a>';
                    $(modal_footer).html("");
                    $(modal_footer).html(html_f);
                    $('#PatientModal').modal('toggle');
                    $('#ListContentModal').modal('toggle');
                    // $('.file').val('');
                }
            });
            return false;
        });
        // Search locale
        $('.submit_l').on('click', function () {
            searchDB();
        });


    });


/*  success: function (response) {
    let n = response.length;
    message.innerHTML = ""
    if (n > 0) {

        $.each(response, function (key, item) {
            data.push({ lien: response[key].links, resultat: response[key].resultat });
            // $(message).append('<li> Plagiat detecté <a target="_blank" href="' + response[key].links + ' "> ' + response[key].links + '</a> avec un taux de similarité de <span class="text-primary">'  + response[key].resultat + '</span> </li>');
        });
        function comparerAgeDecroissant(a, b) {
            return b.resultat - a.resultat;
        }
        data.sort(comparerAgeDecroissant);
        data.forEach(function (element) {
            $(message).append('<li> Plagiat detecté <a target="_blank" href="' + element.lien + ' "> ' + element.lien + '</a> avec un taux de similarité de <span class="text-primary">' + element.resultat + '</span> </li>');
        });
    } else {
        $(message).append('<li> Aucun plagiats  </li>');
    }

    // $('.file').val('');
}
 */
