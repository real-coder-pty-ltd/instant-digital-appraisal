jQuery(document).ready(function ($) {
    $address = '.domain-address-container input';

    if ($('.domain-address-container .suggest-container').length === 0) {
        $('.domain-address-container .ginput_container').append(`
            <div class="suggest-container position-absolute w-100 d-none">
                <ul class="list-group list-unstyled bg-white text-start"></ul>
            </div>
        `);
    }

    $($address).on('input', function () {
        var query = $(this).val();
        $('#appraisal-submit').prop('disabled', true);

        $.ajax({
            url: domain_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'domain_address_suggest',
                query: query,
            },
            success: function (response) {
                // Handle the response from the server
                // console.log(response);
                console.log(response);

                if (response.success) {
                    var suggestions = response.data;
                    console.log(suggestions);

                    var suggestContainer = $('.domain-address-container .suggest-container ul');
                    suggestContainer.empty();

                    suggestions.forEach(function (suggestion) {
                        var li = $('<li></li>')
                            .text(suggestion.address)
                            .attr('data-id', suggestion.id)
                            .addClass('list-group-item px-3');
                        suggestContainer.append(li);
                    });

                    $('.domain-address-container .suggest-container').removeClass('d-none');
                    $('#appraisal-submit').prop('disabled', true);
                }
            },
            error: function (error) {
                // Handle any errors
                // console.log(error);
                console.log(error);
            }
        });
    });

    $(document).on('click', '.domain-address-container .suggest-container ul li', function () {
        var address = $(this).text();
        var id = $(this).attr('data-id');
        $($address).val(address);

        // Ensure the input field exists and update its value
        var idInput = $('.domain-id-container input');
        if (idInput.length) {
            idInput.val(id);
        } else {
            console.error('Input field not found');
        }

        $('#appraisal-submit').prop('disabled', false);

        // Hide the suggestion container
        $('.domain-address-container .suggest-container').addClass('d-none');
    });
});