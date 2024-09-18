<style>
    /* Basic styling for the search input and suggestion box */
    #searchInput {
        width: 300px;
        padding: 8px;
        font-size: 16px;
    }

    #suggestions {
        border: 1px solid #ccc;
        border-top: none;
        max-height: 200px;
        overflow-y: auto;
        position: absolute;
        background: white;
        width: 400px;
        z-index: 1000;
        display: none;
    }

    #suggestions li {
        padding: 8px 0px 8px 0px;
        cursor: pointer;
        margin: 0px 0px 0px -13px;
    }

    #suggestions li:hover {
        background-color: #f0f0f0;
    }
</style>
<form method="POST" class="d-block ajaxForm" action="<?php echo route('stop/create'); ?>" enctype="multipart/form-data">
    <div class="form-row">
        <div class="form-group mb-1">
            <label for="stop_name">Stop Name</label>
            <input type="text" class="form-control" name="stop_name" required>
        </div>

        <div class="form-group mb-1">
            <label for="stop_address">Stop Address</label>
            <input type="text" class="form-control" cols="22" name="stop_address" data-validate="required" data-message-required="<?php echo get_phrase('value_required'); ?>" id="address12" autofocus required />
            <ul id="suggestions" style="list-style-type:none;"></ul>
        </div>
        <!-- <div class="form-group mb-1">
            <ul id="formattedList"></ul>
        </div> -->

        <div class="form-group mb-1">
            <label class="control-label"><?php echo get_phrase('latitude'); ?></label>

            <input type="text" class="form-control" name="latitude" id="latitude1" data-validate="required" readonly="readonly" data-message-required="<?php echo get_phrase('value_required'); ?>" value="" autofocus required />

        </div>
        <div class="form-group mb-1">
            <label class="control-label"><?php echo get_phrase('longitude'); ?></label>

            <input type="text" class="form-control" name="longitude" data-validate="required" id="longitude1" readonly="readonly" data-message-required="<?php echo get_phrase('value_required'); ?>" value="" autofocus required />

        </div>

        <div class="form-group mt-2 col-md-12">
            <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('create_vehicle'); ?></button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('select.select2:not(.normal)').each(function() {
            $(this).select2({
                dropdownParent: '#right-modal'
            });
        }); //initSelect2(['#department', '#gender', '#blood_group', '#show_on_website']);
    });


    $(".ajaxForm").validate({}); // Jquery form validation initialization
    $(".ajaxForm").submit(function(e) {
        var form = $(this);
        ajaxSubmit(e, form, showAllVehicles);
    });

    // initCustomFileUploader();

    // $(document).ready(function() {
    //     function mymapCode() {
    //         var adds = $(this).val();
    //         var settings = {
    //             "url": "https://geocode.maps.co/search?q=" + adds + "&api_key=66b5e030650e4241921532xfe93122b",
    //             "method": "GET",
    //             "timeout": 0,
    //         };

    //         $.ajax(settings).done(function(response) {
    //             if ($.isEmptyObject(response)) {
    //                 alert("Address does not exist");
    //             } else {
    //                 $("#latitude1").val(response[0].lat);
    //                 $("#longitude1").val(response[0].lon);
    //             }


    //         });
    //     }

    //     $("#address12").blur((mymapCode));
    // });

    $(document).ready(function() {
        function mymapCode() {
            var address = $(this).val();
            var apiKey = '23308733c2204b7880f9035c0878be01'; // Replace with your Google Maps API key
            const ul = document.getElementById('formattedList');
            const searchInput = document.getElementById('address12');
            const suggestionsList = document.getElementById('suggestions');
            const $latInput = $('#latitude1');
            const $lonInput = $('#longitude1');

            function handleItemClick(formattedValue) {
                searchInput.value = formattedValue;
            }
            $.ajax({
                url: 'https://api.geoapify.com/v1/geocode/autocomplete?text=' + address + '&apiKey=' + apiKey,
                type: 'GET',
                success: function(data) {
                    suggestionsList.innerHTML = '';

                    // Filter and add new suggestions
                    const filteredFeatures = data.features.filter(feature =>
                        feature.properties.formatted.toLowerCase().includes(address.toLowerCase())
                    );

                    if (filteredFeatures.length > 0) {
                        suggestionsList.style.display = 'block';
                        filteredFeatures.forEach(feature => {
                            const li = document.createElement('li');
                            li.textContent = feature.properties.formatted;
                            li.addEventListener('click', () => {
                                searchInput.value = feature.properties.formatted;
                                $latInput.val(feature.geometry.coordinates[1]);
                                $lonInput.val(feature.geometry.coordinates[0]);
                                suggestionsList.style.display = 'none';
                            });
                            suggestionsList.appendChild(li);
                        });
                    } else {
                        suggestionsList.style.display = 'none';
                    }

                    // Hide suggestions when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                            suggestionsList.style.display = 'none';
                        }

                    });

                }
            });
        }

        $("#address12").blur((mymapCode));
    });
</script>