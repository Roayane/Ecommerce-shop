<div>
    <h4>Hello dear {{ $orderDto->customerName }},</h4>

    <p>
        we sorry that your order for <b> {{ $orderDto->productName }} </b>
        <br />
        with the quantity of <b> {{ $orderDto->quantity }} </b>
    </p>

    <b>has been failed.</b>

    <p>Please contact us for more details.</p>

    <p>Thank you!!</p>

</div>