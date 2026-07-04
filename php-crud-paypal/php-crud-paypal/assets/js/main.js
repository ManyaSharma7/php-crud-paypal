const API = 'api/products.php';

const productBody   = document.getElementById('product-body');
const modal          = document.getElementById('modal');
const modalTitle      = document.getElementById('modal-title');
const form            = document.getElementById('product-form');
const checkoutModal   = document.getElementById('checkout-modal');
const checkoutName    = document.getElementById('checkout-product-name');
const checkoutStatus  = document.getElementById('checkout-status');
const paypalContainer = document.getElementById('paypal-button-container');

// ---------- CRUD: READ ----------
async function loadProducts() {
    const res = await fetch(API);
    const products = await res.json();
    productBody.innerHTML = '';
    products.forEach(p => productBody.appendChild(renderRow(p)));
}

function renderRow(p) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${p.id}</td>
        <td>${escapeHtml(p.name)}</td>
        <td>${escapeHtml(p.description || '')}</td>
        <td>$${Number(p.price).toFixed(2)}</td>
        <td>${p.stock}</td>
        <td>
            <button class="btn btn-buy" data-buy="${p.id}" data-name="${escapeHtml(p.name)}">Buy Now</button>
            <button class="btn" data-edit="${p.id}">Edit</button>
            <button class="btn btn-danger" data-delete="${p.id}">Delete</button>
        </td>`;
    return tr;
}

function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
}

// ---------- CRUD: CREATE / UPDATE ----------
document.getElementById('btn-add').addEventListener('click', () => openModal());
document.getElementById('btn-cancel').addEventListener('click', closeModal);

function openModal(product = null) {
    form.reset();
    document.getElementById('product-id').value = product?.id || '';
    modalTitle.textContent = product ? 'Edit Product' : 'Add Product';
    if (product) {
        document.getElementById('name').value = product.name;
        document.getElementById('description').value = product.description;
        document.getElementById('price').value = product.price;
        document.getElementById('stock').value = product.stock;
    }
    modal.classList.remove('hidden');
}
function closeModal() { modal.classList.add('hidden'); }

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('product-id').value;
    const payload = {
        name: document.getElementById('name').value,
        description: document.getElementById('description').value,
        price: parseFloat(document.getElementById('price').value),
        stock: parseInt(document.getElementById('stock').value, 10),
    };

    const url = id ? `${API}?id=${id}` : API;
    const method = id ? 'PUT' : 'POST';

    const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });

    if (res.ok) {
        closeModal();
        loadProducts();
    } else {
        const err = await res.json();
        alert('Error: ' + (err.error || 'Something went wrong'));
    }
});

// ---------- CRUD: DELETE / EDIT (event delegation) ----------
productBody.addEventListener('click', async (e) => {
    const editId   = e.target.dataset.edit;
    const deleteId = e.target.dataset.delete;
    const buyId    = e.target.dataset.buy;

    if (editId) {
        const res = await fetch(`${API}?id=${editId}`);
        const product = await res.json();
        openModal(product);
    }

    if (deleteId) {
        if (!confirm('Delete this product?')) return;
        await fetch(`${API}?id=${deleteId}`, { method: 'DELETE' });
        loadProducts();
    }

    if (buyId) {
        openCheckout(buyId, e.target.dataset.name);
    }
});

// ---------- PayPal Checkout ----------
function openCheckout(productId, productName) {
    checkoutName.textContent = productName;
    checkoutStatus.textContent = '';
    paypalContainer.innerHTML = '';
    checkoutModal.classList.remove('hidden');

    paypal.Buttons({
        createOrder: async () => {
            const res = await fetch('paypal/create-order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId }),
            });
            const data = await res.json();
            if (data.error) {
                checkoutStatus.textContent = 'Error: ' + data.error;
                throw new Error(data.error);
            }
            return data.id;
        },
        onApprove: async (data) => {
            const res = await fetch('paypal/capture-order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: data.orderID }),
            });
            const result = await res.json();
            checkoutStatus.textContent = result.status === 'COMPLETED'
                ? 'Payment successful! Thank you.'
                : 'Payment status: ' + result.status;
        },
        onError: (err) => {
            checkoutStatus.textContent = 'Payment error — see console.';
            console.error(err);
        }
    }).render('#paypal-button-container');
}

document.getElementById('btn-close-checkout').addEventListener('click', () => {
    checkoutModal.classList.add('hidden');
});

// ---------- init ----------
loadProducts();
