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
    <style>
        body {
            display: flex;
            flex-direction: row;
        }

        .order-list {
            width: 50%;
            padding: 10px;
        }

        .preview-container {
            width: 50%;
            padding: 10px;
            border-left: 1px solid #ccc;
            overflow-y: auto;
            height: 100vh;
        }

        .page {
            position: relative;
            width: 148mm;
            /* A5 width */
            height: 210mm;
            /* A5 height */
            padding: 10mm;
            margin-bottom: 20px;
            border: 1px solid #000;
            background: url('path/to/your/background-image.jpg') no-repeat center center;
            background-size: cover;
            box-sizing: border-box;
            page-break-after: always;
        }

        .barcode {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: calc(100% - 20mm);
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
            border-top: 1px solid #000;
        }

        .print-button,
        .preview-button {
            margin-top: 10px;
        }

        @media print {
            .page {
                width: auto;
                height: auto;
                margin: 0;
                border: none;
                page-break-after: always;
            }

            .print-button,
            .preview-button,
            .order-list {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="order-list">
        <h1>Order List</h1>
        <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
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
            <p>Select orders to preview and print.</p>
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

        function showPreview() {
            const selectedOrders = Array.from(document.querySelectorAll('input.order-checkbox:checked'))
                .map(checkbox => parseInt(checkbox.value));
            const previewContainer = document.getElementById('previewContent');
            previewContainer.innerHTML = ''; // Clear existing content

            selectedOrders.forEach(orderId => {
                const order = orders.find(order => order.orderId === orderId);
                if (!order) return;

                const pageDiv = document.createElement('div');
                pageDiv.className = 'page';

                const barcodeImg = document.createElement('img');
                barcodeImg.src = 'data:image/png;base64,' + getBarcodeBase64(orderId);
                barcodeImg.alt = 'Barcode';
                barcodeImg.className = 'barcode';
                pageDiv.appendChild(barcodeImg);

                const orderDetails = document.createElement('div');
                orderDetails.innerHTML = `
                    <h2>Order ID: ${order.orderId}</h2>
                    <p>Customer Name: ${order.customerName}</p>
                    <p>Order Date: ${order.orderDate}</p>
                    <p>Status: ${order.status}</p>
                `;
                pageDiv.appendChild(orderDetails);

                const table = document.createElement('table');
                const tableHeader = `
                    <tr>
                        <th>Item ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                `;
                table.insertAdjacentHTML('beforeend', tableHeader);

                order.details.forEach(detail => {
                    const tableRow = `
                        <tr>
                            <td>${detail.itemId}</td>
                            <td>${detail.itemName}</td>
                            <td>${detail.quantity}</td>
                            <td>${detail.price}</td>
                        </tr>
                    `;
                    table.insertAdjacentHTML('beforeend', tableRow);
                });
                pageDiv.appendChild(table);

                const footer = document.createElement('div');
                footer.className = 'footer';
                footer.innerHTML = `
                    <p>Alamat Toko: Jl. Contoh No.123, Kota Contoh, Indonesia</p>
                    <p>Telepon: (021) 1234567 | Email: info@toko.com</p>
                `;
                pageDiv.appendChild(footer);

                previewContainer.appendChild(pageDiv);
            });
        }

        function printSelected() {
            const printContents = document.getElementById('previewContent').innerHTML;
            const originalContent = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }

        // Automatically show preview on page load
        document.addEventListener('DOMContentLoaded', showPreview);
    </script>
</body>

</html>