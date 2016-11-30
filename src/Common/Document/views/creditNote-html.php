<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Credit Note</title>
</head>
<body>
    <div class="creditNote">
        <div class="header">
            <h1>Credit Note #<?php echo $creditNote->getId(); ?></h1>
            <div class="from">
                <p><?php echo $creditNote->getFrom()->getName(); ?></p>
                <p><?php echo $creditNote->getFrom()->getId(); ?></p>
                <p><?php echo $creditNote->getFrom()->getAddress(); ?></p>
            </div>
            <div class="from">
                <p><?php echo $creditNote->getTo()->getName(); ?></p>
                <p><?php echo $creditNote->getTo()->getId(); ?></p>
                <p><?php echo $creditNote->getTo()->getAddress(); ?></p>
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
                    <?php foreach ($creditNote->getItems() as $item) : ?>
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
                        <td><?php echo $creditNote->getSubtotal(); ?></td>
                    </tr>
                    <?php foreach ($creditNote->getTaxes() as $tax) : ?>
                    <tr class="tax">
                        <td colspan="3" class="right"><?php echo $tax->getName().' ('.$tax->getRate().'%)'; ?></td>
                        <td><?php echo $tax->getAmount($creditNote->getSubtotal()); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total">
                        <td colspan="3" class="right">Total</td>
                        <td><?php echo $creditNote->getTotal(); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="footer">

        </div>
    </div>
</body>
</html>