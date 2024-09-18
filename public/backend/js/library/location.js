(function($) {
    "use strict";
    var HT = {};

    HT.getLocation = () => {
        $(document).on('change', '.location', function() {
            let _this = $(this);
            let option = {
                'data': {
                    'location_id': _this.val(),
                },
                'target': _this.attr('data-target')
            };
            HT.sendDataTogetLocation(option);
        });
    };

    HT.sendDataTogetLocation = (option) => {
        $.ajax({
            url: '/ajax/location/getLocation',  // Đảm bảo đường dẫn này là đúng
            type: 'GET',
            data: option.data,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('.' + option.target).html(res.html);

                    if (option.target === 'districts' && district_id != '') {
                        $('.districts').val(district_id).trigger('change');
                    }

                    if (option.target === 'wards' && ward_id != '') {
                        $('.wards').val(ward_id).trigger('change');
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Lỗi: ' + textStatus + ' ' + errorThrown);
            }
        });
    };

    HT.loadCity = () => {
        if (province_id != '') {
            $(".province").val(province_id).trigger('change');
        }
    }

    $(document).ready(function() {
        HT.getLocation();
        HT.loadCity();
    });
})(jQuery);
