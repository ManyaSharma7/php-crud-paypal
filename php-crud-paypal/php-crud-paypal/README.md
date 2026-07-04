# Product Manager — PHP AJAX CRUD + PayPal Integration

A small product-management app demonstrating:
- **AJAX CRUD** (Create, Read, Update, Delete) with vanilla JS `fetch()` calling a PHP REST-style API, backed by MySQL/PDO — no page reloads.
- **PayPal Checkout integration** (Orders API v2, Sandbox) — a "Buy Now" button per product that creates and captures a real PayPal sandbox order.

## Setup

1. **Database**
   - Import the schema: `mysql -u root -p < schema.sql`
   - Edit `db.php` with your MySQL credentials if different from the defaults.

2. **PayPal Sandbox credentials**
   - Create a free app at https://developer.paypal.com/dashboard/applications/sandbox
   - Copy the **Client ID** and **Secret** into:
     - `paypal/config.php` (`PAYPAL_CLIENT_ID`, `PAYPAL_CLIENT_SECRET`)
     - `index.php` (the PayPal JS SDK `<script>` tag's `client-id` query param — same Client ID)
   - Use a PayPal Sandbox **personal/buyer** test account to actually complete a payment (found under Sandbox > Accounts in the developer dashboard).

3. **Run it**
   - Point a local PHP server at the project root, e.g.:
     ```
     php -S localhost:8000
     ```
   - Visit `http://localhost:8000`

## How the pieces fit together

| Feature | Files |
|---|---|
| List / Add / Edit / Delete products via AJAX | `assets/js/main.js` ↔ `api/products.php` ↔ `db.php` |
| PayPal "Buy Now" button, order creation | `assets/js/main.js` (`paypal.Buttons`) → `paypal/create-order.php` |
| PayPal payment capture | `assets/js/main.js` (`onApprove`) → `paypal/capture-order.php` |
| PayPal auth/API helper | `paypal/config.php` |

Key detail worth understanding (likely to come up if asked): the price used for the PayPal order is **looked up server-side** from the database in `create-order.php`, not trusted from the browser — this prevents someone from tampering with the price client-side before checkout.

## Recording your demo video

A short (2–4 min) screen recording that shows:
1. The product list loading (open browser DevTools → Network tab briefly to show the AJAX call to `api/products.php`, no page reload).
2. Adding a new product — show it appear in the table instantly.
3. Editing a product's price/stock — show the update reflected instantly.
4. Deleting a product — show it disappear instantly.
5. Clicking "Buy Now" on a product, completing the payment with a PayPal **sandbox buyer account**, and the "Payment successful" message appearing.

Free screen recorders: **OBS Studio** (Windows/Mac/Linux, free) or the built-in Xbox Game Bar (Win+G on Windows) / QuickTime screen recording (Mac).
