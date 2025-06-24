<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="assets/admin/assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/admin/assets/vendor/libs/popper/popper.js"></script>
<script src="assets/admin/assets/vendor/js/bootstrap.js"></script>
<script src="assets/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="assets/admin/assets/vendor/js/menu.js"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="assets/admin/assets/vendor/libs/apex-charts/apexcharts.js"></script>

<!-- Main JS -->
<script src="assets/admin/assets/js/main.js"></script>

<!-- Page JS -->
<script src="assets/admin/assets/js/dashboards-analytics.js"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>


<!-- add order script -->
<script>
    // getting service price each row
    $('#selected_service').change(function() {
        let serviceId = $(this).val();

        $.ajax({
            url: 'ajax/get_service_price.php',
            type: 'POST',
            data: {
                id_service: serviceId
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#price').val(response.price)
                }
            }

        })
    })

    $('#add_row_order').click(function(e) {
        // for getting value
        let serviceName = $('#selected_service').find('option:selected').text(),
            serviceId = $('#selected_service').val(),
            qty = $('#selected_qty').val(),
            price = $('#price').val(),
            qtyKilogram = parseInt(qty) / 1000,
            subtotal = parseInt(price) * qtyKilogram;

        // warning if service empty
        if (serviceId == '') {
            alert('Please select a service');
            return false;
        }

        // formating price and subtotal
        let formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        });

        let formattedPrice = formatter.format(price);
        let formattedSubtotal = formatter.format(subtotal);

        // for adding row
        e.preventDefault();
        let newRow = "";
        newRow += "<tr>"
        newRow += "<td>" + serviceName + "</td>"
        newRow += "<input type='hidden' name='id_service[]' value='" + serviceId + "'>"
        newRow += "<td>" + formattedPrice + "</td>"
        newRow += "<td>" + qty + "</td>"
        newRow += "<input type='hidden' name='qty[]' value='" + qty + "'>"
        newRow += "<td>" + formattedSubtotal + "</td>"
        newRow += "<input type='hidden' name='subtotal[]' value='" + subtotal + "' id='subtotal'>"
        newRow += "</tr>"

        let orderTable = $('#order_table');
        orderTable.append(newRow);

        // count total price
        let total_price = 0;
        $('input[name="subtotal[]"]').each(function() {
            let total_price_value = parseFloat($(this).val()) || 0;
            total_price += total_price_value;
        })
        $('#total_price').val(total_price);

        let formattedTotalPrice = formatter.format(total_price);

        $('#total_price_formatted').val(formattedTotalPrice);

        // make input field become default
        $('#selected_service').val("");
        $('#selected_qty').val("");

    })
</script>
<!-- end add order script -->

<!-- add pickup script -->
<script>
    $('#pickup_pay').change(function() {
        let pickupPay = parseFloat($('#pickup_pay').val()) || 0,
            totalPrice = parseFloat($('#total_price_pickup').val()) || 0,
            pickupChange = pickupPay - totalPrice;

        if (pickupChange <= 0) {
            pickupChange = 0;
        }

        // formating price and change
        let formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        });

        $('#pickup_change_formatted').val(formatter.format(pickupChange));
        $('#pickup_change').val(pickupChange);
    })
</script>
<!-- end add pickup script -->