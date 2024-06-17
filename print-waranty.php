<?php
require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

$orders = [
    [
        'orderId' => 1,
        'customerName' => 'John Doe',
        'orderDate' => '2024-06-14',
        'status' => 'Shipped',
        'details' => [
            ['itemId' => 'A123', 'itemName' => 'Laptop', 'quantity' => 1, 'price' => 1200],
            ['itemId' => 'B456', 'itemName' => 'Mouse', 'quantity' => 2, 'price' => 25]
        ]
    ],
    [
        'orderId' => 2,
        'customerName' => 'Jane Smith',
        'orderDate' => '2024-06-13',
        'status' => 'Processing',
        'details' => [
            ['itemId' => 'C789', 'itemName' => 'Keyboard', 'quantity' => 1, 'price' => 75],
            ['itemId' => 'D012', 'itemName' => 'Monitor', 'quantity' => 2, 'price' => 200]
        ]
    ],
    [
        'orderId' => 3,
        'customerName' => 'Bob Johnson',
        'orderDate' => '2024-06-12',
        'status' => 'Delivered',
        'details' => [
            ['itemId' => 'E345', 'itemName' => 'Chair', 'quantity' => 1, 'price' => 150],
            ['itemId' => 'F678', 'itemName' => 'Desk', 'quantity' => 1, 'price' => 300]
        ]
    ]
];

$generator = new BarcodeGeneratorPNG();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Orders</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="order-list">
        <h1>Order List</h1>
        <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)" checked>
        <label for="selectAll">Select All</label>
        <ul>
            <?php foreach ($orders as $order) : ?>
                <li>
                    <input type="checkbox" class="order-checkbox" id="order<?= $order['orderId'] ?>" value="<?= $order['orderId'] ?>" checked onchange="updateSelectAll()">
                    <label for="order<?= $order['orderId'] ?>">Order ID: <?= $order['orderId'] ?></label>
                </li>
            <?php endforeach; ?>
        </ul>
        <button class="preview-button" onclick="showPreview()">Preview Selected</button>
        <button class="print-button" onclick="printSelected()">Print Selected</button>
    </div>
    <div class="preview-container" id="previewContainer">
        <h1>Preview</h1>
        <div id="previewContent">
            <p>Select an order to preview.</p>
        </div>
    </div>

    <script>
        const orders = <?php echo json_encode($orders); ?>;
        const generatorPNG = "<?php echo base64_encode($generator->getBarcode('placeholder', $generator::TYPE_CODE_128)); ?>";

        function getBarcodeBase64(orderId) {
            return generatorPNG.replace('placeholder', orderId);
        }

        function toggleSelectAll(selectAllCheckbox) {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = selectAllCheckbox.checked);
        }

        function updateSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.order-checkbox');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            selectAllCheckbox.checked = allChecked;
        }

        function showPreview(orderId) {

            const selectedOrders = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                .map(checkbox => parseInt(checkbox.value));

            console.log(selectedOrders);

            const previewContents = selectedOrders.map(orderId => {
                const order = orders.find(order => order.orderId === orderId);

                console.log(order);

                if (!order) return '';

                const barcode = getBarcodeBase64(orderId);

                return `
                <div class="page" id="printable">
                    <div class="barcode">
                        <img src="data:image/png;base64,${barcode}" alt="Barcode">
                        <p class="pb1">barcode text</p>
                    </div>
                    <p class="p1">ItemName2</p>
                    <p class="p2">ItemCode</p>
                    <p style="font-size:24px; padding-left:150px; padding-top:100px; font-weight:bold"><span>CneeName</span><br><span style="font-size:12px;margin-top:-40px">CneeName</span></p>
                    <p style="font-size:12px; padding-left:170px; padding-top:70px; font-weight:bold"><span>Rev.ShipDate</span>-<span style="">CneeCode</span><span style="padding-left:50px;">No.Reg:Msitem.Accesory8</span></p>
                </div>
            `;
            }).join('');

            document.getElementById('previewContent').innerHTML = previewContents;
        }

        function printSelected() {
            const printContent = document.getElementById('previewContent').outerHTML;
            const originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }

        // Automatically show preview on page load
        document.addEventListener('DOMContentLoaded', showPreview);
    </script>
</body>

</html>