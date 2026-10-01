/**
 * Cart Management System (LocalStorage + UI Drawer Sync)
 * Dapur Nasi Biryani Berkah
 */

const BiryaniCart = {
  STORAGE_KEY: 'dapur_biryani_cart_v1',

  getCart() {
    try {
      const data = localStorage.getItem(this.STORAGE_KEY);
      return data ? JSON.parse(data) : [];
    } catch (e) {
      console.error('Failed to parse cart data', e);
      return [];
    }
  },

  saveCart(cart) {
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(cart));
    this.updateBadge();
    this.renderDrawer();
  },

  addItem(product, qty = 1, notes = '') {
    const cart = this.getCart();
    const existingIndex = cart.findIndex(item => item.id === product.id);

    if (existingIndex > -1) {
      cart[existingIndex].quantity += qty;
      if (notes) cart[existingIndex].notes = notes;
    } else {
      cart.push({
        id: product.id,
        name: product.name,
        price: parseFloat(product.price),
        image: product.image,
        portion_size: product.portion_size || '1 Porsi',
        quantity: qty,
        notes: notes || ''
      });
    }

    this.saveCart(cart);
    this.animateBadge();
    this.openDrawer();
  },

  updateQuantity(productId, newQty) {
    let cart = this.getCart();
    if (newQty <= 0) {
      cart = cart.filter(item => item.id !== productId);
    } else {
      const item = cart.find(item => item.id === productId);
      if (item) {
        item.quantity = newQty;
      }
    }
    this.saveCart(cart);
  },

  updateNotes(productId, notes) {
    const cart = this.getCart();
    const item = cart.find(item => item.id === productId);
    if (item) {
      item.notes = notes;
      this.saveCart(cart);
    }
  },

  removeItem(productId) {
    const cart = this.getCart().filter(item => item.id !== productId);
    this.saveCart(cart);
  },

  clearCart() {
    localStorage.removeItem(this.STORAGE_KEY);
    this.updateBadge();
    this.renderDrawer();
  },

  getTotal() {
    return this.getCart().reduce((sum, item) => sum + (item.price * item.quantity), 0);
  },

  getCount() {
    return this.getCart().reduce((sum, item) => sum + item.quantity, 0);
  },

  formatRupiah(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
  },

  updateBadge() {
    const badges = document.querySelectorAll('.cart-count-badge');
    const count = this.getCount();
    badges.forEach(badge => {
      badge.textContent = count;
      if (count > 0) {
        badge.classList.remove('hidden');
      } else {
        badge.classList.add('hidden');
      }
    });
  },

  animateBadge() {
    if (typeof gsap !== 'undefined') {
      gsap.fromTo('.cart-count-badge', 
        { scale: 0.6, rotate: -15 }, 
        { scale: 1, rotate: 0, duration: 0.4, ease: 'back.out(2)' }
      );
    }
  },

  openDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const backdrop = document.getElementById('cart-drawer-backdrop');
    const panel = document.getElementById('cart-drawer-panel');
    if (!drawer) return;

    drawer.classList.remove('pointer-events-none');
    backdrop.classList.remove('opacity-0');
    backdrop.classList.add('opacity-100');
    panel.classList.remove('translate-x-full');
    panel.classList.add('translate-x-0');
    document.body.style.overflow = 'hidden';
    this.renderDrawer();
  },

  closeDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const backdrop = document.getElementById('cart-drawer-backdrop');
    const panel = document.getElementById('cart-drawer-panel');
    if (!drawer) return;

    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    panel.classList.remove('translate-x-0');
    panel.classList.add('translate-x-full');
    drawer.classList.add('pointer-events-none');
    document.body.style.overflow = '';
  },

  renderDrawer() {
    const container = document.getElementById('cart-drawer-items');
    const emptyState = document.getElementById('cart-drawer-empty');
    const footer = document.getElementById('cart-drawer-footer');
    const totalEl = document.getElementById('cart-drawer-total');
    if (!container) return;

    const cart = this.getCart();

    if (cart.length === 0) {
      container.innerHTML = '';
      if (emptyState) emptyState.classList.remove('hidden');
      if (footer) footer.classList.add('hidden');
      return;
    }

    if (emptyState) emptyState.classList.add('hidden');
    if (footer) footer.classList.remove('hidden');

    let html = '';
    cart.forEach(item => {
      const subtotal = item.price * item.quantity;
      html += `
        <div class="flex gap-4 p-3.5 bg-white rounded-xl border border-stone-200/80 shadow-sm relative group">
          <img src="${item.image}" alt="${item.name}" class="w-18 h-18 w-20 h-20 object-cover rounded-lg flex-shrink-0 bg-stone-100">
          <div class="flex-1 min-w-0 flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start gap-2">
                <h4 class="font-bold text-stone-900 text-sm leading-snug line-clamp-1">${item.name}</h4>
                <button onclick="BiryaniCart.removeItem(${item.id})" class="text-stone-400 hover:text-rose-600 transition-colors p-1" title="Hapus">
                  <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
              </div>
              <p class="text-xs text-amber-700 font-semibold mt-0.5">${this.formatRupiah(item.price)}</p>
              ${item.portion_size ? `<span class="inline-block text-[10px] text-stone-500 bg-stone-100 px-1.5 py-0.5 rounded mt-1">${item.portion_size}</span>` : ''}
            </div>

            <div class="flex items-center justify-between mt-3 pt-2 border-t border-stone-100">
              <!-- Qty Buttons -->
              <div class="inline-flex items-center rounded-lg border border-stone-200 bg-stone-50 overflow-hidden">
                <button onclick="BiryaniCart.updateQuantity(${item.id}, ${item.quantity - 1})" class="w-7 h-7 flex items-center justify-center text-stone-600 hover:bg-stone-200 transition-colors text-xs font-bold">
                  <i class="fa-solid fa-minus"></i>
                </button>
                <span class="w-8 text-center text-xs font-bold text-stone-900">${item.quantity}</span>
                <button onclick="BiryaniCart.updateQuantity(${item.id}, ${item.quantity + 1})" class="w-7 h-7 flex items-center justify-center text-stone-600 hover:bg-stone-200 transition-colors text-xs font-bold">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
              <!-- Subtotal -->
              <span class="text-xs font-bold text-stone-900">${this.formatRupiah(subtotal)}</span>
            </div>
          </div>
        </div>
      `;
    });

    container.innerHTML = html;
    if (totalEl) totalEl.textContent = this.formatRupiah(this.getTotal());
  }
};

document.addEventListener('DOMContentLoaded', () => {
  BiryaniCart.updateBadge();
  BiryaniCart.renderDrawer();

  // Close drawer with ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') BiryaniCart.closeDrawer();
  });
});
