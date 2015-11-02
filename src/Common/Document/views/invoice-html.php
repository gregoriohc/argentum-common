<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Invoice</title>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <h1>Invoice #<?php echo $invoice->getId(); ?></h1>
            <div class="from">
                <p><?php echo $invoice->getFrom()->getName(); ?></p>
                <p><?php echo $invoice->getFrom()->getId(); ?></p>
                <p><?php echo $invoice->getFrom()->getAddress(); ?></p>
            </div>
            <div class="from">
                <p><?php echo $invoice->getTo()->getName(); ?></p>
                <p><?php echo $invoice->getTo()->getId(); ?></p>
                <p><?php echo $invoice->getTo()->getAddress(); ?></p>
            </div>
        </div>
        <div class="body">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoice->getItems() as $item) : ?>
                    <tr class="item">
                        <td><?php echo $item->getName(); ?></td>
                        <td><?php echo $item->getQuantity(); ?></td>
                        <td><?php echo $item->getPrice(); ?></td>
                        <td><?php echo $item->getQuantity() * $item->getPrice(); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="subtotal">
                        <td colspan="3" class="right">Subtotal</td>
                        <td><?php echo $invoice->getSubtotal(); ?></td>
                    </tr>
                    <?php foreach ($invoice->getTaxes() as $tax) : ?>
                    <tr class="tax">
                        <td colspan="3" class="right"><?php echo $tax->getName() . ' ('.$tax->getRate().'%)'; ?></td>
                        <td><?php echo $tax->getAmount($invoice->getSubtotal()); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total">
                        <td colspan="3" class="right">Total</td>
                        <td><?php echo $invoice->getTotal(); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="footer">

        </div>
    </div>
</body>
</html>