;(function () {

    // User page content tabs
    $(".userPageTabs").on("click", (e) => { userPageTabs(e) })
    $( "#Gallery" ).trigger( "click" );

    function userPageTabs(e) {
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
                async: true,
                success: (output) => {
                    $(".userPageContent").html(output);              
                    $(".content_single").on("click", (e) => { e.stopPropagation(); modalContent(e) })
                },
                error: (xhr, ajaxOptions, thrownError) => {
                    console.log(thrownError)
                }
            });
        }
    }

    // Modal content
    $(".postContent").on("click", (e) => {e.stopPropagation(); modalContent(e) })

    function modalContent(e, values = []) {
        let tar = $(e.target);
        let cont = tar.attr('cont');

        //console.log(values);
        console.log(values);
        if (values[1]) {
            console.log('values[1] - ' + values[1].value);
            console.log($("#content"));

            //var value = $("#content").getBody().html();
            //console.log('value - ' + value);

        }

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
                   // alert(output)
                    let modal = $(".modal");
                    let modalBody = $(".modal-body");
                    modalBody.html(output);
    
                    if (!modal.hasClass('show')) {
                        modal.modal('toggle');
                    }
                    
                    let validateForm = $('#validateForm')
                    if (validateForm != null) {
                        validateForm.submit((e) => {
                            e.preventDefault();
                            let formData = new FormData();
                            console.log(formData);
                            /*
                            let textArea = $("#content");
                            textArea[0].sceditor('instance').updateOriginal();
                            */
                           
                            /*
                            var textArea = $("#content").sceditor('instance');
                            var value = textArea.getBody().html();
                            console.log(validateForm);
                            */
                            let form_data = validateForm.serializeArray();
                            $.each(form_data, function (key, input) {
                                formData.append(input.name, input.value);
                            });
                            
                            //File data
                            let file_data = $('input[name="image"]')[0].files;
                            for (let i = 0; i < file_data.length; i++) {
                                formData.append("image[]", file_data[i]);
                            }

                            modalContent(e, formData)
                        });
                        /*
                        let textArea = $("#content");
                        if (textArea) {
                            sceditor.create(textArea[0], {
                                format: 'bbcode',
                                style: 'minified/themes/content/modern.min.css',
                                toolbarExclude: 'emoticon',
                                emoticonsEnabled: false
                                //emoticonsRoot: 'p5/images/emoticons/',
                            });
                        }
                        */
                    }
    
                    // Valid success
                    $(".postSuccess").on("click", (e) => { postSuccess(e) })

                    // Likes
                    $(".like").on("click", (e) => { like(e) })
    
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
    
}());