$(document).ready(function(){
    $('#myModal').on('hidden.bs.modal', function () {
        $('.err-msg').hide();
    })
    $('#createPrdBtn').on('click', function(e){
        $('.block-loading').show();
        var formData = new FormData($('#addProductForm')[0]);
        e.preventDefault();
        $.ajax({
            url: $('#addProductForm').attr('action'), //this is the submit URL
            type: 'POST', //or POST,
            enctype: "multipart/form-data",
            cache: false,
            contentType: false,
            processData: false,
            async: false,
            data: formData,//$('#addProductForm').serialize(),
            success: function(data){
                $('.block-loading').hide();
                if (data.success) {
                    $('#addProductForm')[0].reset();
                    $('#myModal').modal('hide');
                    window.location.replace( $('#addProductForm').attr('redirect'));
                } else {
                    $('.err-msg').show();
                    $('#err').html(data.message);
                }

            }
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#previewImage').attr('src', e.target.result);
                $('.preview').show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imageInput").change(function(){
        readURL(this);
    });

    $('#getLoc').on('click', function(e){
        e.preventDefault();
        getLocation();
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(setPosition);
        }
    }

    function setPosition(position) {
        $('#lat').val(position.coords.latitude);
        $('#long').val(position.coords.longitude);
    }

    $('.locationShow').on('click', function () {
        var lat = '';
        var long = '';

        if (typeof $(this).data('lat') !== 'undefined') {
            lat = $(this).data('lat');
        }
        if (typeof $(this).data('long') !== 'undefined') {
            long = $(this).data('long');
        }

        var latlon = lat + "," + long;
        var img_url = "http://maps.googleapis.com/maps/api/staticmap?center="
            +latlon+"&zoom=14&size=568x300&sensor=true&markers="+latlon;
        $("#mapholder").html("<img class='img-responsive' src='"+img_url+"'>");

    })

});
