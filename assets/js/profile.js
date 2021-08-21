const $ = require('jquery');

$(document).ready(() => {
    const userAvatar = $('#user_avatar');
    // upload avatar
    userAvatar.change(() => {

            // Loading
            $('#upload_avatar').html("Uploading...")
                .append("<i id='avatar_spinner' class='fas fa-spinner text-green-500 ml-1 animate-spin'></i>")
                .addClass('opacity-50 cursor-not-allowed')
            ;
            // userAvatar.prop('disabled', true)
            //     .removeClass('hover:bg-gray-400')
            // ;

            setTimeout(() => {
                check()
            }, 2000)


            // check for upload
            const check = () => {
                if (Math.round(userAvatar.get(0).files[0].size / 1024) <= 2048 && userAvatar.get(0).files.length > 0) {
                    $('#upload_avatar').html("Uploaded")
                        .append("<i id='avatar_checked' class='fas fa-check text-green-500 ml-1'></i>")
                        .addClass('opacity-50 cursor-not-allowed')
                        .removeClass('hover:bg-gray-400')
                    ;

                } else {
                    $('#upload_avatar').html("Your file is more than 2MB")
                        .append("<i id='avatar_checked' class='fas fa-times text-red-500 ml-1'></i>")
                        .removeClass('opacity-50 cursor-not-allowed')
                    $('#avatar_checked').remove();
                    userAvatar.prop('disabled', false);

                }
            }
        }
    )
})