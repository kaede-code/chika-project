(function() {
    function syncQty(form, qty) {
        var input = form.querySelector('.product-qty-input');
        if (input) input.value = String(qty);
    }

    function getCurrentQty(card) {
        var el = card.querySelector('.pc-qty-value');
        var v = parseInt(el ? el.textContent : '1', 10);
        return Number.isFinite(v) ? v : 1;
    }

    function showPesanOnly(card) {
        var pesan = card.querySelector('.pc-btn-pesan');
        var qtyBox = card.querySelector('.pc-qty-box');
        if (pesan) pesan.style.display = 'flex';
        if (qtyBox) qtyBox.style.display = 'none';
    }

    function showQtyOnly(card) {
        var pesan = card.querySelector('.pc-btn-pesan');
        var qtyBox = card.querySelector('.pc-qty-box');
        if (pesan) pesan.style.display = 'none';
        if (qtyBox) qtyBox.style.display = 'flex';
    }

    var cards = document.querySelectorAll('.product-card');
    cards.forEach(function(card) {
        var form = card.querySelector('.product-action-form');
        if (!form) return;

        var pesanBtn = card.querySelector('.pc-btn-pesan');
        var minusBtn = card.querySelector('.pc-qty-minus');
        var plusBtn = card.querySelector('.pc-qty-plus');

        if (pesanBtn) {
            pesanBtn.addEventListener('click', function(e) {
                e.preventDefault();
                syncQty(form, 1);
                showQtyOnly(card);
                form.submit();
            });
        }

        if (plusBtn) {
            plusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var current = getCurrentQty(card);
                syncQty(form, current + 1);
                showQtyOnly(card);
                form.submit();
            });
        }

        if (minusBtn) {
            minusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var current = getCurrentQty(card);
                var next = Math.max(0, current - 1);
                syncQty(form, next);
                if (next === 0) {
                    showPesanOnly(card);
                } else {
                    showQtyOnly(card);
                }
                form.submit();
            });
        }
    });
})();
