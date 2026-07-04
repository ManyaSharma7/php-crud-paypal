<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Manager — PHP AJAX CRUD + PayPal</title>
<link rel="stylesheet" href="assets/css/style.css">
<!-- PayPal JS SDK - replace client-id with your sandbox client id -->
<script src="https://www.paypal.com/sdk/js?client-id=YOUR_SANDBOX_CLIENT_ID&currency=USD"></script>
</head>
<body>

<div class="container">
    <h1>Product Manager</h1>

    <button id="btn-add" class="btn btn-primary">+ Add Product</button>

    <table id="product-table">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Actions</th>
            </tr>
        </thead>
        <tbody id="product-body">
            <!-- Rows injected by AJAX -->
        </tbody>
    </table>
</div>

<!-- Add / Edit Modal -->
<div id="modal" class="modal hidden">
    <div class="modal-content">
        <h2 id="modal-title">Add Product</h2>
        <form id="product-form">
            <input type="hidden" id="product-id">
            <label>Name <input type="text" id="name" required></label>
            <label>Description <textarea id="description"></textarea></label>
            <label>Price (USD) <input type="number" step="0.01" id="price" required></label>
            <label>Stock <input type="number" id="stock" required></label>
            <div class="modal-actions">
                <button type="button" id="btn-cancel" class="btn">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Checkout Modal (PayPal) -->
<div id="checkout-modal" class="modal hidden">
    <div class="modal-content">
        <h2>Checkout: <span id="checkout-product-name"></span></h2>
        <div id="paypal-button-container"></div>
        <p id="checkout-status"></p>
        <button type="button" id="btn-close-checkout" class="btn">Close</button>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
