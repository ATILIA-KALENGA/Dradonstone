// dragonstone.js - simple student-level JS
// Handles: simple form validation, localStorage cart, basic AJAX calls using fetch
document.addEventListener('DOMContentLoaded', function() {
  // Simple helper to show messages (small non-blocking toast)
  function showToast(msg) {
    let t = document.createElement('div');
    t.textContent = msg;
    t.style.position = 'fixed';
    t.style.right = '16px';
    t.style.bottom = '16px';
    t.style.background = 'rgba(0,0,0,0.8)';
    t.style.color = 'white';
    t.style.padding = '10px 14px';
    t.style.borderRadius = '6px';
    t.style.zIndex = 9999;
    t.style.fontSize = '14px';
    document.body.appendChild(t);
    setTimeout(()=> t.remove(), 2500);
  }

  // --------- Form validation (login/register) ----------
  function bindFormValidation(selector) {
    const form = document.querySelector(selector);
    if (!form) return;
    form.addEventListener('submit', function(e) {
      const email = form.querySelector('[name="email"]');
      const pwd = form.querySelector('[name="password"]');
      if (email && !/^\S+@\S+\.\S+$/.test(email.value.trim())) {
        e.preventDefault();
        showToast('Veuillez entrer un email valide.');
        email.focus();
        return false;
      }
      if (pwd && pwd.value.length < 6) {
        e.preventDefault();
        showToast('Le mot de passe doit contenir au moins 6 caractères.');
        pwd.focus();
        return false;
      }
      // allow submit
    });
  }

  bindFormValidation('form[action="register.php"], form[action="login_user.php"], form[action="login_user.php"]');
  // Some sites use relative actions; bind more generally
  bindFormValidation('form#registerForm');
  bindFormValidation('form#loginForm');

  // --------- Simple Cart using localStorage ----------
  const CART_KEY = 'dragonstone_cart_v1';

  function getCart() {
    try {
      return JSON.parse(localStorage.getItem(CART_KEY) || '[]');
    } catch (e) {
      return [];
    }
  }
  function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateCartUI();
  }

  function addToCart(product) {
    const cart = getCart();
    // check if product exists by id
    const found = cart.find(p => p.id == product.id);
    if (found) {
      found.qty = (found.qty || 1) + 1;
    } else {
      product.qty = product.qty || 1;
      cart.push(product);
    }
    saveCart(cart);
    showToast('Produit ajouté au panier');
  }

  function removeFromCart(productId) {
    let cart = getCart();
    cart = cart.filter(p => p.id != productId);
    saveCart(cart);
    showToast('Produit supprimé');
  }

  function updateCartItemQty(productId, qty) {
    const cart = getCart();
    const found = cart.find(p => p.id == productId);
    if (found) {
      found.qty = Math.max(1, parseInt(qty) || 1);
      saveCart(cart);
    }
  }

  function clearCart() {
    localStorage.removeItem(CART_KEY);
    updateCartUI();
  }

  function cartTotal() {
    const cart = getCart();
    return cart.reduce((sum, p) => sum + (parseFloat(p.price || 0) * (p.qty || 1)), 0);
  }

  // UI updates: looks for elements with data-cart-count and data-cart-total
  function updateCartUI() {
    const cart = getCart();
    const countEls = document.querySelectorAll('[data-cart-count]');
    const totalEls = document.querySelectorAll('[data-cart-total]');
    const count = cart.reduce((s,p)=> s + (p.qty||1), 0);
    countEls.forEach(el => el.textContent = count);
    totalEls.forEach(el => el.textContent = cartTotal().toFixed(2));
  }

  // Bind clicks for add-to-cart buttons (they should have class .add-to-cart and data attributes)
  document.body.addEventListener('click', function(e) {
    const btn = e.target.closest('.add-to-cart');
    if (!btn) return;
    const id = btn.getAttribute('data-id');
    const title = btn.getAttribute('data-title') || btn.getAttribute('data-name') || 'Produit';
    const price = parseFloat(btn.getAttribute('data-price') || '0');
    if (!id) {
      showToast('Produit invalide');
      return;
    }
    addToCart({id: id, title: title, price: price});
  });

  // Simple handler to render cart items into a container with id #cartItems
  function renderCartItems() {
    const container = document.getElementById('cartItems');
    if (!container) return;
    const cart = getCart();
    container.innerHTML = '';
    if (cart.length === 0) {
      container.innerHTML = '<p>Panier vide</p>';
      return;
    }
    cart.forEach(item => {
      const div = document.createElement('div');
      div.className = 'cart-item';
      div.innerHTML = `
        <strong>${item.title}</strong> — ${Number(item.price).toFixed(2)} €
        <br>Quantité: <input type="number" min="1" value="${item.qty}" data-cart-qty="${item.id}" style="width:60px">
        <button data-cart-remove="${item.id}">Supprimer</button>
      `;
      container.appendChild(div);
    });
  }

  // Listen changes in quantities and remove buttons
  document.body.addEventListener('input', function(e) {
    const el = e.target;
    if (el && el.hasAttribute('data-cart-qty')) {
      const id = el.getAttribute('data-cart-qty');
      updateCartItemQty(id, el.value);
      renderCartItems();
    }
  });
  document.body.addEventListener('click', function(e) {
    const rem = e.target.closest('[data-cart-remove]');
    if (rem) {
      const id = rem.getAttribute('data-cart-remove');
      removeFromCart(id);
      renderCartItems();
    }
  });

  // Checkout: send cart to server if /api/place_order.php exists
  const checkoutBtn = document.querySelector('[data-action="checkout"]');
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', function(e) {
      const cart = getCart();
      if (!cart.length) {
        showToast('Le panier est vide');
        return;
      }
      // simple POST using fetch to api/place_order.php
      fetch('/ecommerce-project/api/place_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart: cart })
      }).then(r=>r.json()).then(data=>{
        if (data.success) {
          showToast('Commande passée avec succès');
          clearCart();
          // optional redirect
          if (data.redirect) window.location = data.redirect;
        } else {
          showToast(data.error || 'Erreur lors de la commande');
        }
      }).catch(err=>{
        console.error(err);
        showToast('Erreur réseau');
      });
    });
  }

  // Initial UI setup
  renderCartItems();
  updateCartUI();

  // Expose some functions for console/testing
  window.Dragonstone = {
    getCart, saveCart, addToCart, removeFromCart, clearCart, cartTotal, renderCartItems
  };
});