
<script>
$(document).ready(function() {
    $('#add_row_order').click(function(e) {
        e.preventDefault(); // Mencegah form tersubmit saat tombol ini diklik

        // Dapatkan elemen option yang sedang dipilih
        let selectedOption = $('#selected_service').find('option:selected');
        
        // Ambil semua data dari elemen yang dipilih
        let serviceId = selectedOption.val();
        let serviceName = selectedOption.text();
        let price = selectedOption.data('price'); // Ambil harga dari data-price
        let qty = $('#selected_qty').val();

        // Validasi input: pastikan semua terisi
        if (!serviceId || !qty || qty <= 0) {
            alert('Please select a service and enter a valid quantity.');
            return; // Hentikan eksekusi jika tidak valid
        }

        // Konversi dan kalkulasi (pastikan semua adalah angka)
        let priceFloat = parseFloat(price) || 0;
        let qtyFloat = parseFloat(qty) || 0;
        let qtyKilogram = qtyFloat / 1000;
        let subtotal = priceFloat * qtyKilogram;

        // Siapkan format mata uang
        let formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        });

        let formattedPrice = formatter.format(priceFloat);
        let formattedSubtotal = formatter.format(subtotal);

        // Buat baris tabel baru
        let newRow = `
            <tr>
                <td>
                    ${serviceName}
                    <input type='hidden' name='id_service[]' value='${serviceId}'>
                </td>
                <td>${formattedPrice}</td>
                <td>
                    ${qty}
                    <input type='hidden' name='qty[]' value='${qty}'>
                </td>
                <td>
                    ${formattedSubtotal}
                    <input type='hidden' name='subtotal[]' class='subtotal-val' value='${subtotal}'>
                </td>
            </tr>
        `;

        // Tambahkan baris baru ke tabel
        $('#order_table').append(newRow);

        // Hitung ulang total harga
        updateTotalPrice();

        // Kosongkan kembali input
        $('#selected_service').val("");
        $('#selected_qty').val("");
    });

    function updateTotalPrice() {
        let total_price = 0;
        // Loop setiap input subtotal dan jumlahkan nilainya
        $('.subtotal-val').each(function() {
            total_price += parseFloat($(this).val()) || 0;
        });
        
        // Siapkan format mata uang
        let formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        });
        let formattedTotalPrice = formatter.format(total_price);
        
        // Update nilai di input total harga
        $('#total_price_formatted').val(formattedTotalPrice);
        $('#total_price').val(total_price);
    }
});
</script>
<!-- end add order script -->
<!-- end add order script -->
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