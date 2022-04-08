function load_cart_data_() {
    $.ajax({
        url: "fetch_cart.php",
        method: "POST",
        dataType: "json",
        success: function(data) {
            $('#cart_details').html(data.cart_details);
            //$('.total_price').text(data.total_price);
            //$('.badge').text(data.total_item);
        }
    });
}

function quantityChange(pid) {
    var pid = pid;
    var quantity = $('#input_quantity' + pid).val();
    var action = "changeQuantity";
    if (quantity > 0) {
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { pid: pid, quantity: quantity, action: action },
            success: function(data) {
                load_cart_data_();
                //alert("Item has been Added into Cart");
            }
        });
    } else {
        alert("Quantity has to be >0");
    }
}

$(document).ready(function() {

    load_cart_data();

    function load_cart_data() {
        $.ajax({
            url: "fetch_cart.php",
            method: "POST",
            dataType: "json",
            success: function(data) {
                $('#cart_details').html(data.cart_details);
                //$('.total_price').text(data.total_price);
                //$('.badge').text(data.total_item);
            }
        });
    }

    $(document).on('click', '.add_to_cart', function() {
        var pid = $(this).attr("id");
        var name = $('#name' + pid + '').val();
        var price = $('#price' + pid + '').val();
        var quantity = $('#quantity' + pid).val();
        var action = "add";
        if (quantity > 0) {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { pid: pid, name: name, price: price, quantity: quantity, action: action },
                success: function(data) {
                    load_cart_data();
                    //alert("Item has been Added into Cart");
                }
            });
        }
    });

    $(document).on('click', '.delete', function() {
        var pid = $(this).attr("id");
        var action = 'remove';
        if (confirm("Are you sure you want to remove this product?")) {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { pid: pid, action: action },
                success: function() {
                    load_cart_data();
                }
            })
        } else {
            return false;
        }
    });

    $(document).on('click', '#clear_cart', function() {
        var action = 'empty';
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { action: action },
            success: function() {
                load_cart_data();
                alert("Your Cart has been clear");
            }
        });
    });
});