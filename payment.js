/* Submit form data */
async function submitFormData(event) {
    /* Send data to server by AJAX */
    const params = {
        "currency_code": event.currency_code.value,
        "business": event.business.value,
        "total_item": event.total_item.value,
        "em": event.em.value,
        //"item_name_1": event.item_name_1.value,
        //"quantity_1": event.quantity_1.value,
        //"item_name_2": event.item_name_2.value,
        //"quantity_2": event.quantity_2.value
    };

    for (let i = 1; i < parseInt(event.total_item.value) + 1; i++) {
        params['item_name_' + i] = event['item_name_' + i].value;
        params['quantity_' + i] = event['quantity_' + i].value;
    }

    let success = true;
    await fetch('/order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(params).toString()
        })
        .then(response => response.json())
        .then(data => {
            /* Fill invoice and digest back into invoice and custom */
            event.invoice.value = data.invoice;
            event.custom.value = data.digest;
            console.log("Data:", data);
        })
        .catch((error) => {
            success = false;
            console.error('Error:', error);
        });

    if (success) {
        /* Submit form to redirect to Paypal */
        event.action = "payments.php";
        event.method = "post";
        event.submit();
    } else {
        //err msg
    }
}