/*!
 * =================================================================
 * Theme Name: Velvet Vogue Fashion Store
 * Version: 1.0.0
 * Purpose: Product details page — gallery thumbnails, tabs, selectors, quantity
 * =================================================================
 */
document.addEventListener('DOMContentLoaded', function () {
  "use strict";

  /* --- Thumbnail Gallery --- */
  var mainImg = document.getElementById('vvfsPdMainImg');
  var thumbs = document.querySelectorAll('.vvfs-pd-thumb');
  if (mainImg && thumbs.length) {
    thumbs.forEach(function (thumb) {
      thumb.addEventListener('click', function () {
        var newSrc = thumb.getAttribute('data-img');
        if (newSrc && mainImg.src !== newSrc) {
          mainImg.style.opacity = '0';
          setTimeout(function () {
            mainImg.src = newSrc;
            mainImg.style.opacity = '1';
          }, 200);
        }
        thumbs.forEach(function (t) {
          t.classList.remove('border-rose-500', 'opacity-100');
          t.classList.add('border-transparent', 'opacity-60');
        });
        thumb.classList.remove('border-transparent', 'opacity-60');
        thumb.classList.add('border-rose-500', 'opacity-100');
      });
    });
  }

  /* --- Wishlist Buttons --- */
  var wishBtn = document.getElementById('vvfsPdWishlistBtn');
  var wishBtnText = document.getElementById('vvfsPdWishlist');
  var wishActive = false;

  function toggleWishlist() {
    wishActive = !wishActive;
    if (wishActive) {
      if (wishBtn) {
        wishBtn.querySelector('i').className = 'fa-solid fa-heart';
        wishBtn.style.background = '#f43f5e';
        wishBtn.style.borderColor = '#f43f5e';
      }
      if (wishBtnText) {
        wishBtnText.innerHTML = '<i class="fa-solid fa-heart"></i> Wishlisted';
        wishBtnText.style.borderColor = '#f43f5e';
        wishBtnText.style.color = '#f43f5e';
      }
    } else {
      if (wishBtn) {
        wishBtn.querySelector('i').className = 'fa-regular fa-heart';
        wishBtn.style.background = '';
        wishBtn.style.borderColor = '';
      }
      if (wishBtnText) {
        wishBtnText.innerHTML = '<i class="fa-regular fa-heart"></i> Add to Wishlist';
        wishBtnText.style.borderColor = '';
        wishBtnText.style.color = '';
      }
    }
  }

  if (wishBtn) wishBtn.addEventListener('click', toggleWishlist);
  if (wishBtnText) wishBtnText.addEventListener('click', toggleWishlist);

  /* --- Tab Navigation --- */
  var tabBtns = document.querySelectorAll('.vvfs-pd-tab-btn');
  var tabContents = document.querySelectorAll('.vvfs-pd-tab-content');
  if (tabBtns.length) {
    tabBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var tab = btn.getAttribute('data-tab');
        tabBtns.forEach(function (b) {
          b.classList.remove('text-rose-500', 'font-semibold', 'border-rose-500');
          b.classList.add('text-zinc-500', 'font-medium', 'border-transparent');
        });
        btn.classList.add('text-rose-500', 'font-semibold', 'border-rose-500');
        btn.classList.remove('text-zinc-500', 'font-medium', 'border-transparent');

        tabContents.forEach(function (c) { c.classList.add('hidden'); });
        var target = document.getElementById('vvfsPdTab-' + tab);
        if (target) target.classList.remove('hidden');
      });
    });
  }

  /* --- Color Selector --- */
  var colorBtns = document.querySelectorAll('.vvfs-pd-color-opt');
  var colorInput = document.querySelector('.vvfs-pd-attr-input[data-attribute="attribute_pa_color"]');
  var selectedColorLabel = document.getElementById('vvfsPdSelectedColor');
  if (colorBtns.length) {
    colorBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        colorBtns.forEach(function (b) {
          b.classList.remove('border-white', 'shadow-lg');
          b.classList.add('border-transparent');
        });
        btn.classList.add('border-white', 'shadow-lg');
        btn.classList.remove('border-transparent');
        if (colorInput) colorInput.value = btn.getAttribute('data-value');
        if (selectedColorLabel) selectedColorLabel.textContent = btn.getAttribute('data-color');
        tryMatchVariation();
      });
    });
  }

  /* --- Size Selector --- */
  var sizeBtns = document.querySelectorAll('.vvfs-pd-size-opt');
  var sizeInput = document.querySelector('.vvfs-pd-attr-input[data-attribute="attribute_pa_size"]');
  var selectedSizeLabel = document.getElementById('vvfsPdSelectedSize');
  if (sizeBtns.length) {
    sizeBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (btn.disabled) return;
        sizeBtns.forEach(function (b) {
          b.classList.remove('bg-rose-500', 'text-white', 'border-rose-500', 'shadow-lg', 'shadow-rose-500/30');
          b.classList.add('bg-white/5', 'text-zinc-300', 'border-white/10');
        });
        btn.classList.add('bg-rose-500', 'text-white', 'border-rose-500', 'shadow-lg', 'shadow-rose-500/30');
        btn.classList.remove('bg-white/5', 'text-zinc-300', 'border-white/10');
        if (sizeInput) sizeInput.value = btn.getAttribute('data-value');
        if (selectedSizeLabel) selectedSizeLabel.textContent = btn.getAttribute('data-size');
        tryMatchVariation();
      });
    });
  }

  /* --- Variation Matching --- */
  function tryMatchVariation() {
    var form = document.querySelector('.variations_form');
    if (!form) return;
    var variationsData = form.data('product_variations');
    if (!variationsData || variationsData === 'false') return;

    var colorVal = colorInput ? colorInput.value : '';
    var sizeVal = sizeInput ? sizeInput.value : '';

    var matched = null;
    variationsData.forEach(function (v) {
      var attrs = v.attributes || {};
      var colorMatch = !colorVal || (attrs.attribute_pa_color && attrs.attribute_pa_color.toLowerCase() === colorVal.toLowerCase());
      var sizeMatch = !sizeVal || (attrs.attribute_pa_size && attrs.attribute_pa_size.toLowerCase() === sizeVal.toLowerCase());
      if (colorMatch && sizeMatch) matched = v;
    });

    var variationIdInput = document.querySelector('.vvfs-pd-variation-id');
    if (matched) {
      if (variationIdInput) variationIdInput.value = matched.variation_id;
      if (matched.image && matched.image.src && mainImg) {
        mainImg.style.opacity = '0';
        setTimeout(function () {
          mainImg.src = matched.image.src;
          mainImg.style.opacity = '1';
        }, 200);
      }
    } else {
      if (variationIdInput) variationIdInput.value = '';
    }
  }

  /* --- Quantity +/- --- */
  var qtyInput = document.getElementById('vvfsPdQtyValue');
  var qtyMinus = document.getElementById('vvfsPdQtyMinus');
  var qtyPlus = document.getElementById('vvfsPdQtyPlus');
  if (qtyInput && qtyMinus && qtyPlus) {
    qtyMinus.addEventListener('click', function () {
      var v = parseInt(qtyInput.value) || 1;
      if (v > 1) qtyInput.value = v - 1;
    });
    qtyPlus.addEventListener('click', function () {
      var v = parseInt(qtyInput.value) || 1;
      qtyInput.value = v + 1;
    });
  }

  /* --- AJAX Add to Cart (single product) --- */
  var pdForm = document.querySelector('.variations_form.cart, form.cart');
  var pdAddBtn = document.querySelector('.vvfs-pd-add-to-cart, .single_add_to_cart_button');
  if (pdForm && pdAddBtn) {
    pdForm.addEventListener('submit', function (e) {
      e.preventDefault();

      var originalBtnHTML = pdAddBtn.innerHTML;
      pdAddBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
      pdAddBtn.disabled = true;

      var fd = new FormData(pdForm);
      var pid = pdForm.getAttribute('data-product_id') || pdForm.querySelector('[name="add-to-cart"]')?.value || '';
      if (pid && !fd.has('product_id')) fd.append('product_id', pid);

      var wcAjaxUrl = (typeof vvfsAjax !== 'undefined')
        ? vvfsAjax.ajax_url.replace('/wp-admin/admin-ajax.php', '') + '/?wc-ajax=add_to_cart'
        : '/?wc-ajax=add_to_cart';

      fetch(wcAjaxUrl, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then(function (r) { return r.json(); })
        .then(function () {
          pdAddBtn.innerHTML = '<i class="fa-solid fa-check"></i> Added to Cart';

          var productTitle = '';
          var pdTitle = document.querySelector('h1');
          if (pdTitle) productTitle = pdTitle.textContent.trim();

          if (typeof vvfsShowToast === 'function') {
            vvfsShowToast(productTitle || 'Added to cart', 'View your cart for checkout');
          }

          setTimeout(function () {
            pdAddBtn.innerHTML = originalBtnHTML;
            pdAddBtn.disabled = false;
          }, 2500);
        })
        .catch(function () {
          pdAddBtn.innerHTML = originalBtnHTML;
          pdAddBtn.disabled = false;
        });
    });
  }

  /* --- Buy Now --- */
  var buyNowBtn = document.getElementById('vvfsPdBuyNow');
  var form = document.querySelector('.variations_form');
  if (buyNowBtn) {
    buyNowBtn.addEventListener('click', function () {
      if (!form) return;

      buyNowBtn.disabled = true;
      buyNowBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

      var tmpForm = document.createElement('form');
      tmpForm.method = 'POST';
      tmpForm.action = form.action || window.location.href;

      var fields = {
        'add-to-cart': form.querySelector('[name="add-to-cart"]') ? form.querySelector('[name="add-to-cart"]').value : form.getAttribute('data-product_id'),
        'quantity': qtyInput ? qtyInput.value : 1,
        'buy_now': '1'
      };

      Object.keys(fields).forEach(function (key) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = fields[key];
        tmpForm.appendChild(input);
      });

      var varInput = document.querySelector('.vvfs-pd-variation-id');
      if (varInput && varInput.value) {
        var vi = document.createElement('input');
        vi.type = 'hidden';
        vi.name = 'variation_id';
        vi.value = varInput.value;
        tmpForm.appendChild(vi);
      }

      document.body.appendChild(tmpForm);
      tmpForm.submit();
    });
  }
});
