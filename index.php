<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Orders</title>
    <style>
        @media print {
            .page {
                page-break-after: always;
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

            .barcode {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 50%;
                /* Menyesuaikan ukuran barcode menjadi setengah */
            }
        }
    </style>
</head>

<body>
    <div id="orderContainer"></div>
    <button onclick="window.print()">Print Orders</button>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        const orders = [{
                orderId: 2024061400000001,
                customerName: 'John Doe',
                orderDate: '2024-06-14',
                status: 'Shipped',
                details: [{
                        itemId: 'A123',
                        itemName: 'Laptop',
                        quantity: 1,
                        price: 1200
                    },
                    {
                        itemId: 'B456',
                        itemName: 'Mouse',
                        quantity: 2,
                        price: 25
                    }
                ]
            },
            {
                orderId: 2024061400000002,
                customerName: 'Jane Smith',
                orderDate: '2024-06-13',
                status: 'Processing',
                details: [{
                        itemId: 'C789',
                        itemName: 'Keyboard',
                        quantity: 1,
                        price: 75
                    },
                    {
                        itemId: 'D012',
                        itemName: 'Monitor',
                        quantity: 2,
                        price: 200
                    }
                ]
            },
            {
                orderId: 2024061400000004,
                customerName: 'Bob Johnson',
                orderDate: '2024-06-12',
                status: 'Delivered',
                details: [{
                        itemId: 'E345',
                        itemName: 'Chair',
                        quantity: 1,
                        price: 150
                    },
                    {
                        itemId: 'F678',
                        itemName: 'Desk',
                        quantity: 1,
                        price: 300
                    }
                ]
            }
        ];

        const orderContainer = document.getElementById('orderContainer');

        orders.forEach(order => {
            const orderDiv = document.createElement('div');
            orderDiv.className = 'page';

            const barcodeCanvas = document.createElement('canvas');
            barcodeCanvas.className = 'barcode';
            JsBarcode(barcodeCanvas, order.orderId.toString(), {
                format: 'CODE128',
                width: 2,
                height: 20
            });

            const orderHeader = document.createElement('div');
            orderHeader.innerHTML = `<h2>Order ID: ${order.orderId}</h2>
                                 <p>Customer Name: ${order.customerName}</p>
                                 <p>Order Date: ${order.orderDate}</p>
                                 <p>Status: ${order.status}</p>`;

            const orderTable = document.createElement('table');
            orderTable.innerHTML = `<tr>
                                    <th>Item ID</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>`;

            order.details.forEach(detail => {
                const row = document.createElement('tr');
                row.innerHTML = `<td>${detail.itemId}</td>
                             <td>${detail.itemName}</td>
                             <td>${detail.quantity}</td>
                             <td>${detail.price}</td>`;
                orderTable.appendChild(row);
            });

            orderDiv.appendChild(barcodeCanvas);
            orderDiv.appendChild(orderHeader);
            orderDiv.appendChild(orderTable);
            orderContainer.appendChild(orderDiv);
        });
    </script>

</body>

</html>