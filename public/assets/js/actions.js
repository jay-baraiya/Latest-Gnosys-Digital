$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    $(document).on('click', '#delete_action', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');

        // $('#delete_modal').modal('show');
        
        if (url) {
            // $('#delete_modal').find('#delete_modal_confirm').attr('data-encrypted-url', url);
        }

    });

    $(document).on('click', '#delete_modal_confirm', function(e) {
        e.preventDefault();
        let url = $(this).attr('data-encrypted-url');
        
        if (url) {
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function(response) {
                    $('#delete_modal').modal('hide');
                    $('#delete_modal_confirm').attr('data-encrypted-url', 'null');
                    
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }
    });
});