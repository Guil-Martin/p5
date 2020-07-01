;(function () {

    // Home events
    //$(".content_single").on("click", (e) => { modalContent(e) })
    $("#filters").submit((e) => {
        e.preventDefault();
        let validateForm = $(e.target);
        let formData = new FormData();
        let form_data = validateForm.serializeArray();
        $.each(form_data, function (key, input) {
            formData.append(input.name, input.value);
        });
        homeContent(e, formData)
    });

    $("#filters").trigger( "submit" );     

    function homeContent(e, values = []) {
        let tar = $(e.target);
        let cont = tar.attr('cont');

        if (!cont) {
            tar = tar.parent();
            cont = tar.attr('cont');
        }
        if (cont) {
            $.ajax ({ 
                url: cont,
                data: values,
                processData: false,
                contentType: false,
                type: 'POST',
                success: (output) => {
                    $(".homePageContent").html(output);
                    $(".page-link").on("click", (e) => { homeContent(e) })
                    $(".content_single").on("click", (e) => { modalContent(e) })
                    let pageSelect = $('#pageSelect')
                    if (pageSelect != null) {
                        pageSelect.submit((e) => {
                            e.preventDefault();
                            $(".submit").attr("disabled", true);
                            $('.enableOnInput').prop('disabled', true);
                            let formData = new FormData();
                            let form_data = pageSelect.serializeArray();
                            $.each(form_data, function (key, input) {
                                formData.append(input.name, input.value);
                            });
                            homeContent(e, formData)
                        });
                    }
                },
                error: (xhr, ajaxOptions, thrownError) => { console.log(thrownError) }
            });
        }
    }

    // User page content tabs
    $(".userPageTabs").on("click", (e) => { userPageTabs(e) })
    $("#Gallery").trigger( "click" );    
    
    function userPageTabs(e, values = []) {
        let tab = $(e.target);
        let cont = tab.attr('cont');

        if (!cont) {
            tab = tab.parent();
            cont = tab.attr('cont');
        }
        if (cont) {
            tab.siblings().removeClass('active')
            tab.addClass("active");

            $.ajax ({ 
                url: cont,
                data: values,
                processData: false,
                contentType: false,
                type: 'POST',
                success: (output) => {
                    $(".userPageContent").html(output);                    
                    $(".page-link").on("click", (e) => { userPageTabs(e) })
                    $(".content_single").on("click", (e) => { modalContent(e) })
                    let pageSelect = $('#pageSelect')
                    if (pageSelect != null) {
                        pageSelect.submit((e) => {
                            e.preventDefault();                            
                            $(".submit").attr("disabled", true);
                            let formData = new FormData();
                            let form_data = pageSelect.serializeArray();
                            $.each(form_data, function (key, input) {
                                formData.append(input.name, input.value);
                            });
                            userPageTabs(e, formData)
                        });
                    }
                },
                error: (xhr, ajaxOptions, thrownError) => {
                    console.log(thrownError)
                }
            });
        }
    }

    // Modal content
    $(".postContent").on("click", (e) => { modalContent(e) })

    function modalContent(e, values = []) {
        let tar = $(e.target);
        let cont = tar.attr('cont');
        if (!cont) {
            tar = tar.parent();
            cont = tar.attr('cont');
        }
        if (tar.hasClass('del')) { 
            if (confirm('Confirmer ?')) {
                let modalBody = $(".modal-body");
            } else { return; }            
        }
        if (cont) {
            $.ajax ({ 
                url: cont,
                data: values,
                processData: false,
                contentType: false,
                type: 'POST',
                success: (output) => {
                   // alert(output)
                    let modal = $(".modal");
                    let modalBody = $(".modal-body");
                    modalBody.html(output);
    
                    if (tar.hasClass('del')) { 
                        $("#Nouvelles").trigger( "click" );
                    } else {
                        if (!modal.hasClass('show')) {
                            modal.modal('toggle');
                        }
                    }
                    
                    let validateForm = $('#validateForm')
                    if (validateForm != null) {

                        /* BBCODE editor */
                        let textArea = $("#postContent");
                        if (textArea) {
                           // textArea.richText();
                        }

                        tinymce.remove();
                        tinymce.init({
                            selector: '#postContent',                            
                           /* plugins: "bbcode",
                            bbcode_dialect: "punbb",*/
                            language: 'fr_FR',
                            width: '100%',
                            height: 500,
                            skin: 'oxide-dark',
                            content_css: 'dark',
                            entity_encoding : "raw"
                        });

                        validateForm.submit((e) => {
                            e.preventDefault();
                            $(".submit").attr("disabled", true);
                            let formData = new FormData();

                            /* BBCODE editor */
                            //wswgEditor.doCheck();

                            let form_data = validateForm.serializeArray();
                            $.each(form_data, function (key, input) {
                                formData.append(input.name, input.value);
                            });
                            
                            //File data
                            let fileInput = $('input[name="fileUpload"]');                            
                            if (fileInput[0]) {
                                let file_data = $('input[name="fileUpload"]')[0].files;
                                for (let i = 0; i < file_data.length; i++) {
                                    formData.append("fileUpload[]", file_data[i]);
                                }
                            }

                            modalContent(e, formData)
                        });

                    }
    
                    // Valid success
                    $(".postSuccess").on("click", (e) => { postSuccess(e) })

                    // Likes
                    $(".like").on("click", (e) => { like(e) })

                    // del
                    $(".delCom").on("click", (e) => { delCom(e) })
    
                },
                error: (xhr, ajaxOptions, error) => {
                    console.log(error)
                }
            });
        }
    }

    function postSuccess(e) {
        let tar = $(e.target);
        let cont = tar.attr('cont');
        if (cont) {
            let modal = $(".modal");
            if (modal.hasClass('show')) {
                modal.modal('toggle');
            }
            $( "#"+cont ).trigger( "click" );
        }
    }
    
    function like(e) {
        let tar = $(e.target);
        let cont = tar.attr('cont');
        if (!cont) {
            tar = tar.parent();
            cont = tar.attr('cont');
        }
        if (cont) {
            $.ajax ({ 
                url: cont,
                success: (output) => {
                    if (tar.hasClass('btn-secondary')) {
                        tar.removeClass('btn-secondary').addClass('btn-danger')
                    } else {
                        tar.removeClass('btn-danger').addClass('btn-secondary')
                    }                
                },
                error: (xhr, ajaxOptions, error) => {
                    console.log(error)
                }
            });
        }
    }

    function delCom(e) {
        let tar = $(e.target);
        let cont = tar.attr('cont');
        if (!cont) {
            tar = tar.parent();
            cont = tar.attr('cont');
        }
        if (cont) {
            if (confirm('Confirmer ?')) {         
                $.ajax ({ 
                    url: cont,
                    success: (output) => {
                        location.reload();
                    },
                    error: (xhr, ajaxOptions, error) => {
                        console.log(error)
                    }
                });
            }
        }
    }
    
}());