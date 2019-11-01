var LeverFileInput = {
    init: function () {
        jQuery('.lever-file-input').on('click', function () {
            jQuery(this).find('input[type="file"]').click();
        });

        jQuery('.lever-file-input input[type="file"]').on('click', function (e) {
            e.stopPropagation();
        });

        jQuery('.lever-file-input input[type="file"]').change(function (e) {
            var fileName = jQuery(this).val();

            if (fileName === "") {
                jQuery(this).parent().find('.default-label').show();
                jQuery(this).parent().find('.filename').hide();
                return;
            }

            jQuery(this).parent().find('.default-label').hide();
            jQuery(this).parent().find('.filename').text(fileName.replace(/^.*[\\\/]/, '')).show();

            console.log(fileName);
        });
    },
};

LeverFileInput.init();