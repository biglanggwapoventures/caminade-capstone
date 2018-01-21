jQuery(document).ready(function($) {
    $('.modal').on('show.bs.modal', function() {
        var form = $(this).find('form');
        if(!form.length) return;
        form[0].reset();
        form.find('.invalid-feedback').remove();
        form.find('.is-invalid').removeClass('is-invalid')
    })

    $('form.ajax').submit(function (e) {
        e.preventDefault();

        var $this = $(this),
            submitBtn = $this.find('[type=submit]'),
            originalText = submitBtn.text(),
            formData = new FormData($this[0]);

        $this.find('.invalid-feedback').remove();
        $this.find('.is-invalid').removeClass('is-invalid')

        submitBtn.attr('disabled', 'disabled').html('<i class="fa fa-spin fa-spinner"></i> Loading...')

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                if(res.hasOwnProperty('next_url')){
                    window.location.href = res.next_url;
                }else if($this.data('next-url')){
                    window.location.href = $this.data('next-url');
                }else{
                    window.location.reload();
                }
            },
            error: function (err) {
                if(err.status == 422){
                    // alertify.alert('Ooops!', 'Some fields contain errors. Please verify that all inputs are valid and try submitting again.');
                    var errors = err.responseJSON['errors'];

                    for(var field in errors){
                        var fieldName = field;
                        if(field.indexOf('.') !== -1){
                            var parts = field.split('.'),
                                name = parts.splice(0, 1),
                                newField = name+'['+parts.join('][')+']';

                            fieldName = newField;
                        }

                        console.log(fieldName)

                        var input = $this.find("[name=\""+fieldName+"\"]");
                        input.addClass('is-invalid');
                        input.closest('.form-group').append('<div class="invalid-feedback">'+errors[field][0]+'</div>');

                    }
                }else{
                    window.alert('An internal server error has occured. Please refresh the page. If the error still persists. Please contact your system administrator.');
                }
            },
            complete: function () {
                submitBtn.removeAttr('disabled').text(originalText);
            }
        })
    })
    $('body').on('click', '.trash-row', function () {
        if(!confirm('Are you sure you want to delete this entry? This action cannot be undone!')) return;
        $(this).closest('form').submit();
    });

    $('.logout').click(function () {
        if(!confirm('Are you sure you want to logout?')) return;
        $('#logout-form').submit();
    })
});
