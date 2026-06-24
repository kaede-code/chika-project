<div class="order-item">

    <div class="oi-left">
        <img src="{{ $img }}" alt="">
    </div>

    <div class="oi-mid">

        <h4>
            {{ $name }}
        </h4>

        <p>
            Rp {{ number_format($price) }}
        </p>

    </div>

    <div class="oi-right">

        <button class="qty-btn minus">
            -
        </button>

        <span class="qty">
            {{ $qty }}
        </span>

        <button class="qty-btn plus">
            +
        </button>

    </div>

</div>