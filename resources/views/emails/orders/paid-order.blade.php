<div>
    <h4>Hello dear {{ $orderDto->customerName }},</h4>

    <p>
        Your order for <b> {{ $orderDto->productName }} </b>
        <br />
        with the quantity of <b> {{ $orderDto->quantity }} </b>
    </p>

    <b>has been successfully paid.</b>

    <p>Thank you!!</p>
</div>
