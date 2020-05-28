;(function () {
    
    
    /*
	$(".reportBtn").on("click", (e) => {
		let tar = $(e.target);
		let comID = tar.attr('comID');
		let newsID = tar.attr('comNewsID');
		let Url = '/phpBlog/news/report/' + comID + '/' + newsID;
	
		$.ajax({ url: Url,
			data: {action: 'test'},
			type: 'post',
			success: function(output) {
				tar.prop("disabled", true);
				tar.removeClass("btn-danger").addClass("btn-warning");
			}
		});
    })

    let validate = confirm("Voulez-vous vraiment supprimer cet article ?");
    if (validate) {
    }
    */

    // User page content tabs
    $(".userPageTabs").on("click", (e) => { userPageTabs(e) })

    function userPageTabs(e) {
        e.preventDefault();
        let tab = $(e.target);
        let cont = tab.attr('cont');
        $.ajax ({ 
            url: cont,
            data: {action: ''},
            type: 'POST',
            success: (output) => {                

                $("#content").html(output);

               // tab.tab('show');
                
                $(".content_single").on("click", (e) => { modalContent(e) })
            },
            error: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError)
            }
        });
    }

    // Modal content
    $(".postContent").on("click", (e) => { modalContent(e) })

    function modalContent(e, values = []) {
        let tar = $(e.target);
        let cont = tar.attr('cont');
        $.ajax ({ 
            url: cont,
            //dataType: "json",
            data: values,
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
                        modalContent(e, validateForm.serializeArray())
                    });
                }

            },
            error: (xhr, ajaxOptions, error) => {
                console.log(error)
            }
        });
    }

}());