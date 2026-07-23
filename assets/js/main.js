/*!
 * =================================================================
 * Theme Name: Velvet Vogue Fashion Store
 * Version: 1.3.0
 * Purpose: Main application logic — mobile nav, slider, interactions
 * =================================================================
 */

/* --- Toast Notification (global) --- */
function vvfsShowToast(title, subtitle) {
  var existing = document.querySelector('.vvfs-toast');
  if (existing) existing.remove();

  var toast = document.createElement('div');
  toast.className = 'vvfs-toast';
  toast.innerHTML =
    '<div class="vvfs-toast__icon"><i class="fa-solid fa-check"></i></div>' +
    '<div class="vvfs-toast__text">' +
      '<span class="vvfs-toast__title">' + (title || 'Added to cart') + '</span>' +
      (subtitle ? '<span class="vvfs-toast__subtitle">' + subtitle + '</span>' : '') +
    '</div>' +
    '<button class="vvfs-toast__close" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>' +
    '<div class="vvfs-toast__progress"></div>';
  document.body.appendChild(toast);

  var progress = toast.querySelector('.vvfs-toast__progress');
  var duration = 3500;
  var closeBtn = toast.querySelector('.vvfs-toast__close');

  function dismiss() {
    toast.classList.remove('vvfs-toast--visible');
    toast.classList.add('vvfs-toast--hiding');
    setTimeout(function () { toast.remove(); }, 400);
  }

  closeBtn.addEventListener('click', dismiss);

  requestAnimationFrame(function () {
    toast.classList.add('vvfs-toast--visible');
    progress.style.width = '100%';
    progress.style.transition = 'width ' + duration + 'ms linear';
    requestAnimationFrame(function () {
      progress.style.width = '0%';
    });
  });

  setTimeout(dismiss, duration);
}

document.addEventListener('DOMContentLoaded', function () {
  "use strict";

  /* --- Mobile hamburger --- */
  var hamburger = document.getElementById('hamburger');
  var mobileMenu = document.getElementById('mobile-menu');
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      var isOpen = mobileMenu.classList.toggle('open');
      hamburger.innerHTML = isOpen
        ? '<i class="fa-solid fa-xmark"></i>'
        : '<i class="fa-solid fa-bars"></i>';
    });
    mobileMenu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        mobileMenu.classList.remove('open');
        hamburger.innerHTML = '<i class="fa-solid fa-bars"></i>';
      });
    });
  }

  /* --- Hero Slider --- */
  var sliderTrack = document.getElementById('sliderTrack');
  if (sliderTrack) {
    var slides     = sliderTrack.querySelectorAll('.min-w-full');
    var dots       = document.querySelectorAll('.slider-dot');
    var prevBtn    = document.getElementById('prevSlide');
    var nextBtn    = document.getElementById('nextSlide');
    var slideCount = slides.length;
    var current    = 0;
    var autoplayTimer;

    function goToSlide(index) {
      if (index < 0) index = slideCount - 1;
      if (index >= slideCount) index = 0;
      current = index;
      sliderTrack.style.transform = 'translateX(-' + (100 * current) + '%)';
      dots.forEach(function (dot, i) {
        if (i === current) {
          dot.classList.add('active');
          dot.style.background = '#f43f5e';
          dot.style.width = '2.5rem';
        } else {
          dot.classList.remove('active');
          dot.style.background = 'rgba(255,255,255,0.3)';
          dot.style.width = '0.75rem';
        }
      });
    }

    function nextSlide() { goToSlide(current + 1); }
    function prevSlideFn() { goToSlide(current - 1); }
    function startAutoplay() { autoplayTimer = setInterval(nextSlide, 5500); }
    function resetAutoplay() { clearInterval(autoplayTimer); startAutoplay(); }

    dots.forEach(function (dot, i) {
      dot.addEventListener('click', function () { goToSlide(i); resetAutoplay(); });
    });
    if (prevBtn) prevBtn.addEventListener('click', function () { prevSlideFn(); resetAutoplay(); });
    if (nextBtn) nextBtn.addEventListener('click', function () { nextSlide(); resetAutoplay(); });

    goToSlide(0);
    startAutoplay();

    var sliderWrapper = sliderTrack.closest('.relative');
    if (sliderWrapper) {
      sliderWrapper.addEventListener('mouseenter', function () { clearInterval(autoplayTimer); });
      sliderWrapper.addEventListener('mouseleave', function () { startAutoplay(); });

      var touchStartX = 0;
      var touchEndX   = 0;
      sliderWrapper.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].screenX;
      }, { passive: true });
      sliderWrapper.addEventListener('touchend', function (e) {
        touchEndX = e.changedTouches[0].screenX;
        var diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 40) {
          if (diff > 0) nextSlide(); else prevSlideFn();
          resetAutoplay();
        }
      }, { passive: true });
    }
  }

  /* --- AJAX Add to Cart --- */
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.add_to_cart_button');
    if (!btn) return;
    e.preventDefault();

    var productId = btn.getAttribute('data-product_id');
    if (!productId || typeof vvfsAjax === 'undefined') return;

    var productName = '';
    var card = btn.closest('.vvfs-product-card') || btn.closest('.product-card') || btn.closest('li.product');
    if (card) {
      var titleEl = card.querySelector('.woocommerce-loop-product__title, .product-title, h3 a, h2');
      if (titleEl) productName = titleEl.textContent.trim();
    }

    var originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
    btn.style.pointerEvents = 'none';

    var fd = new FormData();
    fd.append('product_id', productId);
    fd.append('quantity', 1);

    var wcAjaxUrl = vvfsAjax.ajax_url.replace('/wp-admin/admin-ajax.php', '') + '/?wc-ajax=add_to_cart';

    fetch(wcAjaxUrl, {
      method: 'POST',
      body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(function (r) { return r.json(); })
      .then(function () {
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Added';
        vvfsShowToast(productName || 'Added to cart', 'View your cart for checkout');
        setTimeout(function () {
          btn.innerHTML = originalHTML;
          btn.style.pointerEvents = '';
        }, 2000);
      })
      .catch(function () {
        btn.innerHTML = originalHTML;
        btn.style.pointerEvents = '';
      });
  });

  /* --- Show toast after page-reload add-to-cart (single product pages) --- */
  var urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('add-to-cart')) {
    var addedId = urlParams.get('add-to-cart');
    var productTitle = '';
    var pdTitle = document.querySelector('.product_title, h1.entry-title, h1');
    if (pdTitle) productTitle = pdTitle.textContent.trim();
    vvfsShowToast(productTitle || 'Added to cart', 'View your cart for checkout');
    urlParams.delete('add-to-cart');
    var cleanUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
    window.history.replaceState({}, '', cleanUrl);
  }

  /* ================================================================
     SIDEBAR FILTERS (Price, Size, Color) — client-side
     ================================================================ */
  var filterCards = document.querySelectorAll('.vvfs-product-card[data-price]');
  var shopGrid = document.querySelector('.vvfs-product-grid');
  var filterCount = document.getElementById('filterCount');
  if (!filterCards.length) return;

  var activeSizes = [];
  var activeColors = [];
  var filterLoader = null;
  var loaderTimer = null;
  var priceRangeEl = document.getElementById('sidebarPriceRange');
  var priceValEl = document.getElementById('sidebarPriceVal');
  var priceMinEl = document.getElementById('priceMin');
  var priceMaxEl = document.getElementById('priceMax');

  function showLoader() {
    if (!shopGrid) return;
    if (!filterLoader) {
      filterLoader = document.createElement('div');
      filterLoader.className = 'vvfs-filter-loader';
      filterLoader.innerHTML = '<div class="vvfs-filter-spinner"></div>';
    }
    shopGrid.appendChild(filterLoader);
    filterLoader.style.display = '';
  }

  function hideLoader() {
    if (filterLoader) {
      filterLoader.style.display = 'none';
    }
  }

  function runFilter() {
    var minVal = priceMinEl ? (parseFloat(priceMinEl.value) || 0) : 0;
    var maxVal = priceRangeEl ? (parseFloat(priceRangeEl.value) || 300) : 300;
    if (priceMaxEl && priceMaxEl.value) maxVal = parseFloat(priceMaxEl.value) || maxVal;

    var visible = 0;
    filterCards.forEach(function (card) {
      var price = parseFloat(card.getAttribute('data-price')) || 0;
      var sizesRaw = card.getAttribute('data-sizes') || '';
      var sizes = sizesRaw ? sizesRaw.split(',').map(function (s) { return s.trim(); }).filter(Boolean) : [];
      var color = (card.getAttribute('data-color') || '').trim();

      var matchPrice = price >= minVal && price <= maxVal;
      var matchSize = activeSizes.length === 0 || activeSizes.some(function (s) { return sizes.indexOf(s) > -1; });
      var matchColor = activeColors.length === 0 || activeColors.indexOf(color) > -1;

      if (matchPrice && matchSize && matchColor) {
        card.style.display = '';
        visible++;
      } else {
        card.style.display = 'none';
      }
    });
    if (filterCount) filterCount.textContent = visible + ' Products';
  }

  function applyFilters(showLoading) {
    if (showLoading) {
      showLoader();
      if (loaderTimer) clearTimeout(loaderTimer);
      loaderTimer = setTimeout(function () {
        runFilter();
        setTimeout(hideLoader, 350);
      }, 400);
    } else {
      runFilter();
    }
  }

  /* --- Price Range Slider --- */
  if (priceRangeEl) {
    priceRangeEl.addEventListener('input', function () {
      if (priceValEl) priceValEl.textContent = priceRangeEl.value;
      if (priceMaxEl) priceMaxEl.value = priceRangeEl.value;
      runFilter();
    });
  }
  if (priceMinEl) {
    priceMinEl.addEventListener('input', function () { runFilter(); });
  }
  if (priceMaxEl) {
    priceMaxEl.addEventListener('input', function () {
      if (priceRangeEl) priceRangeEl.value = priceMaxEl.value;
      if (priceValEl) priceValEl.textContent = priceMaxEl.value;
      runFilter();
    });
  }

  /* --- Size Buttons --- */
  document.querySelectorAll('.vvfs-size-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var size = btn.getAttribute('data-size');
      var idx = activeSizes.indexOf(size);
      if (idx > -1) {
        activeSizes.splice(idx, 1);
        btn.classList.remove('bg-rose-500', 'text-white', 'border-rose-500', 'shadow-lg', 'shadow-rose-500/30');
        btn.classList.add('bg-white/5', 'text-zinc-300', 'border-white/10');
      } else {
        activeSizes.push(size);
        btn.classList.remove('bg-white/5', 'text-zinc-300', 'border-white/10');
        btn.classList.add('bg-rose-500', 'text-white', 'border-rose-500', 'shadow-lg', 'shadow-rose-500/30');
      }
      applyFilters(true);
    });
  });

  /* --- Color Buttons --- */
  document.querySelectorAll('.vvfs-color-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var color = btn.getAttribute('data-color');
      var idx = activeColors.indexOf(color);
      if (idx > -1) {
        activeColors.splice(idx, 1);
        btn.classList.remove('border-white', 'scale-110');
        btn.classList.add('border-transparent');
      } else {
        activeColors.push(color);
        btn.classList.remove('border-transparent');
        btn.classList.add('border-white', 'scale-110');
      }
      applyFilters(true);
    });
  });

  /* --- Clear Filters --- */
  document.querySelectorAll('.vvfs-clear-filters').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      activeSizes = [];
      activeColors = [];
      if (priceRangeEl) { priceRangeEl.value = 300; }
      if (priceValEl) { priceValEl.textContent = '300'; }
      if (priceMinEl) { priceMinEl.value = ''; }
      if (priceMaxEl) { priceMaxEl.value = '300'; }

      document.querySelectorAll('.vvfs-size-btn').forEach(function (btn) {
        btn.classList.remove('bg-rose-500', 'text-white', 'border-rose-500', 'shadow-lg', 'shadow-rose-500/30');
        btn.classList.add('bg-white/5', 'text-zinc-300', 'border-white/10');
      });
      document.querySelectorAll('.vvfs-color-btn').forEach(function (btn) {
        btn.classList.remove('border-white', 'scale-110');
        btn.classList.add('border-transparent');
      });
      applyFilters(true);
    });
  });

  runFilter();
});
