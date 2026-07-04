# 🛒 PHP AJAX CRUD Product Manager with PayPal Integration

A simple Product Management System built using **Core PHP**, **MySQL**, **JavaScript (Fetch API/AJAX)**, **Bootstrap 5**, and **PayPal Sandbox API**. The application allows users to perform CRUD operations without refreshing the page and demonstrates payment gateway integration using PayPal Sandbox.

---

## 🚀 Features

- ✅ Add Product
- ✅ View Products
- ✅ Edit Product
- ✅ Delete Product
- ✅ AJAX-based CRUD (no page refresh)
- ✅ Responsive Bootstrap UI
- ✅ REST-style PHP API
- ✅ PayPal Sandbox Checkout Integration

---

## 🛠️ Technologies Used

- PHP
- MySQL
- JavaScript (Fetch API for AJAX)
- Bootstrap 5
- PayPal Sandbox API
- XAMPP

---

## 📂 Project Structure

```
php-crud-paypal/
│
├── api/
│   └── products.php
├── assets/
│   ├── css/
│   └── js/
├── paypal/
├── index.php
├── db.php
├── schema.sql
└── README.md
```

---

## ⚙️ Installation

1. Clone this repository

```bash
git clone https://github.com/ManyaSharma7/php-crud-paypal.git
```

2. Copy the project into the XAMPP `htdocs` folder.

3. Start **Apache** and **MySQL** from the XAMPP Control Panel.

4. Create a MySQL database:

```
crud_paypal
```

5. Import the `schema.sql` file using phpMyAdmin.

6. Update database settings in `db.php` if required.

7. Configure your own **PayPal Sandbox Client ID** and **Secret**.

8. Open the project:

```
http://localhost/php-crud-paypal/
```

---

## 💳 PayPal Sandbox Configuration

This project uses **PayPal Sandbox** for testing payments.

Before running:

- Create your own Sandbox application from the PayPal Developer Dashboard.
- Replace the placeholder Client ID and Secret with your own Sandbox credentials.

> **Note:** Do not commit real PayPal credentials to public repositories.

---

## 📸 Screenshots

### Home Page

_Add a screenshot here._

### Add Product

_Add a screenshot here._

### Edit Product

_Add a screenshot here._

### Delete Product

_Add a screenshot here._

### AJAX Implementation

_Add a screenshot of the Fetch API/AJAX code here._

### PayPal Checkout

_Add a screenshot here._

---

## 🎯 Learning Outcomes

This project demonstrates:

- Core PHP CRUD Operations
- REST-style API Development
- MySQL Database Integration
- AJAX using JavaScript Fetch API
- Bootstrap-based Responsive UI
- PayPal Sandbox Payment Integration

---

## 👩‍💻 Author

**Manya Sharma**

GitHub: https://github.com/ManyaSharma7
