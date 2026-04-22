<?php
/**
 * Front Page Template — Premium Home
 * Hero slider (5 images via Customizer) + stacked product sections.
 *
 * @package Shopys
 */

get_header();

// ── Pull slider images from Customizer (shared defaults from hero-slider-settings.php) ──
$_slider_defaults = shopys_hero_slider_defaults();
$slider_images    = array();
for ( $i = 1; $i <= 5; $i++ ) {
    $url = get_theme_mod( 'shopys_hero_slide_' . $i, $_slider_defaults[ $i ] );
    if ( $url ) {
        $slider_images[] = esc_url( $url );
    }
}
$slide_count = count( $slider_images );
?>

<style>
:root {
    --accent:       #13e800;
    --accent-dim:   #0fb500;
    --dark:         #0d1117;
    --dark-2:       #111820;
    --dark-3:       #1a2233;
    --card-border:  #e8edf3;
    --text-primary: #111827;
    --text-muted:   #6b7280;
    --radius:       12px;
    --shadow:       0 4px 24px rgba(0,0,0,.08);
    --shadow-hover: 0 12px 40px rgba(0,0,0,.16);
    --transition:   .25s ease;
}

/* ── FRONT-PAGE SEARCH BAR OVERRIDE ─────────────────────────── */
.home #aps-wrapper,
.home .aps-wrapper {
    background: #ffffff;
    max-width: 100%;
    margin: 0;
    padding: 16px 6% 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    border-bottom: 1px solid #eaeaea;
}
.home .aps-wrapper .aps-form {
    max-width: 860px;
    margin: 0 auto;
}
.home .aps-wrapper .aps-input-group {
    background: #f8f9fa !important;
    border: 1.5px solid #e2e6ea !important;
    box-shadow: none !important;
    border-radius: 10px;
}
.home .aps-wrapper .aps-input-group:focus-within {
    background: #fff !important;
    border-color: #13e800 !important;
    box-shadow: 0 0 0 3px rgba(19,232,0,.15) !important;
}
.home .aps-wrapper .aps-submit-btn {
    border-radius: 0 9px 9px 0 !important;
}
.home .fp-hero { margin-top: 0; }

/* ── HERO SLIDER ─────────────────────────────────────────────── */
.fp-hero {
    position: relative;
    width: 100%;
    height: 480px;
    overflow: hidden;
    background: var(--dark);
    user-select: none;
}
.fp-slides {
    display: flex;
    height: 100%;
    transition: transform .7s cubic-bezier(.77,0,.18,1);
    will-change: transform;
}
.fp-slide {
    flex: 0 0 100%;
    height: 100%;
    position: relative;
}
.fp-slide__img {
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    pointer-events: none;
}
.fp-slide__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(13,17,23,.82) 0%, rgba(13,17,23,.28) 55%, transparent 100%);
    display: flex;
    align-items: center;
    padding: 0 7%;
}
.fp-slide__content { max-width: 520px; color: #fff; }

.fp-hero__tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(19,232,0,.15);
    border: 1px solid rgba(19,232,0,.35);
    color: var(--accent);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 50px;
    margin-bottom: 18px;
}
.fp-hero__tag::before {
    content: '';
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--accent);
    animation: fp-pulse 1.6s ease-in-out infinite;
}
@keyframes fp-pulse {
    0%,100%{ opacity:1; transform:scale(1); }
    50%{ opacity:.45; transform:scale(1.5); }
}
.fp-hero__title {
    font-size: clamp(26px, 3.8vw, 50px);
    font-weight: 800;
    line-height: 1.1;
    margin: 0 0 14px;
    letter-spacing: -.5px;
}
.fp-hero__title span { color: var(--accent); }
.fp-hero__sub {
    font-size: 15px;
    opacity: .72;
    margin-bottom: 26px;
    line-height: 1.65;
}
.fp-hero__cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--accent);
    color: #000;
    font-weight: 700;
    font-size: 14px;
    padding: 12px 26px;
    border-radius: 8px;
    text-decoration: none;
    transition: var(--transition);
}
.fp-hero__cta:hover {
    background: #fff; color: #000;
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(19,232,0,.35);
    text-decoration: none;
}
.fp-hero__cta svg { transition: transform .2s; }
.fp-hero__cta:hover svg { transform: translateX(4px); }

/* Arrows */
.fp-arrow {
    position: absolute;
    top: 50%; transform: translateY(-50%);
    z-index: 20;
    background: rgba(0,0,0,.45);
    border: 1px solid rgba(255,255,255,.2);
    color: #fff;
    width: 44px; height: 44px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background .2s, color .2s, border-color .2s;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    padding: 0;
}
.fp-arrow:hover { background: var(--accent); color: #000; border-color: var(--accent); }
.fp-arrow--prev { left: 18px; }
.fp-arrow--next { right: 18px; }

/* Dots */
.fp-dots {
    position: absolute;
    bottom: 16px; left: 50%; transform: translateX(-50%);
    display: flex; gap: 8px; z-index: 20;
    padding: 0; margin: 0; list-style: none;
}
.fp-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,.4);
    cursor: pointer;
    transition: background .25s, transform .25s;
    border: none; padding: 0;
}
.fp-dot.active { background: var(--accent); transform: scale(1.4); }

/* ── CATEGORY QUICK-NAV ──────────────────────────────────────── */
.fp-cats {
    background: #ffffff;
    padding: 22px 5%;
    border-top: 1px solid #eaeaea;
    border-bottom: 1px solid #eaeaea;
}
.fp-cats__inner {
    max-width: 1280px;
    margin: 0 auto;
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}
.fp-cats__item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    padding: 13px 22px;
    border-radius: var(--radius);
    border: 1.5px solid #e2e6ea;
    background: #f8f9fa;
    color: #444;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .6px;
    text-transform: uppercase;
    transition: var(--transition);
    min-width: 100px;
}
.fp-cats__item:hover {
    background: #f0fff0;
    border-color: var(--accent);
    color: var(--accent-dim);
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(19,232,0,.15);
    text-decoration: none;
}
.fp-cats__icon { width: 34px; height: 34px; fill: currentColor; }

/* ── TRUST BAR ───────────────────────────────────────────────── */
.fp-trust {
    background: #f8f9fa;
    padding: 14px 5%;
    border-bottom: 1px solid #eaeaea;
}
.fp-trust__inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 10px;
}
.fp-trust__item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #555;
    font-size: 13px;
}
.fp-trust__item svg { width: 22px; height: 22px; color: var(--accent-dim); flex-shrink: 0; }
.fp-trust__item strong { display: block; color: #222; font-size: 13px; }

/* ── PRODUCTS AREA ───────────────────────────────────────────── */
.fp-products {
    background: #ffffff;
    padding: 24px 5% 68px;
}
.fp-products__inner { max-width: 1380px; margin: 0 auto; }

.fp-cat-section { margin-bottom: 60px; }
.fp-cat-section:last-child { margin-bottom: 0; }

.fp-section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
}
.fp-section-head__left { display: flex; align-items: center; gap: 14px; }
.fp-section-head__bar {
    width: 4px; height: 34px;
    background: var(--accent);
    border-radius: 4px;
    flex-shrink: 0;
}
.fp-section-head__title {
    font-size: 21px;
    font-weight: 800;
    color: var(--dark);
    margin: 0;
    letter-spacing: -.3px;
}
.fp-section-head__count { font-size: 13px; color: var(--text-muted); }
.fp-section-head__link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 700;
    color: var(--accent-dim);
    text-decoration: none;
    padding: 8px 18px;
    border: 1.5px solid var(--accent);
    border-radius: 6px;
    transition: var(--transition);
}
.fp-section-head__link:hover {
    background: var(--accent); color: #000;
    text-decoration: none; transform: translateY(-1px);
}

.fp-cat-section .ppg-grid { margin-top: 0; }
.fp-cat-section .ppg-card {
    border-radius: var(--radius);
    background: #fff;
    border: 1px solid #eaedf0;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    transition: box-shadow var(--transition), transform var(--transition), border-color var(--transition);
}
.fp-cat-section .ppg-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,.12);
    border-color: #d0d5da;
    transform: translateY(-4px);
}

/* Add to Cart / View button — white background friendly */
.fp-cat-section .ppg-card .button,
.fp-cat-section .ppg-card .add_to_cart_button,
.fp-cat-section .ppg-card .ppg-view-btn {
    background: #13e800 !important;
    color: #000 !important;
    border: none !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    transition: background .2s, transform .2s !important;
}
.fp-cat-section .ppg-card .button:hover,
.fp-cat-section .ppg-card .add_to_cart_button:hover,
.fp-cat-section .ppg-card .ppg-view-btn:hover {
    background: #0fb500 !important;
    color: #000 !important;
    transform: translateY(-1px) !important;
}

.fp-cat-section .ppg-pagination { display: none; }

/* ── RESPONSIVE ──────────────────────────────────────────────── */
@media (max-width: 768px) {
    .fp-hero { height: 300px; }
    .fp-products { padding: 34px 4% 50px; }
    .fp-section-head__title { font-size: 18px; }
    .fp-cats__item { min-width: 80px; padding: 10px 14px; }
    .fp-arrow { width: 36px; height: 36px; }
}
</style>

<!-- ═══════════════════════════ TRUST BAR ════════════════════════════ -->
<div class="fp-trust">
    <div class="fp-trust__inner">
        <div class="fp-trust__item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 4v4h-7V8zM1 16h20M5.5 21a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM18.5 21a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>
            <div><strong>Fast Delivery</strong>Phnom Penh &amp; nationwide</div>
        </div>
        <div class="fp-trust__item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <div><strong>Official Warranty</strong>All products guaranteed</div>
        </div>
        <div class="fp-trust__item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            <div><strong>After-Sales Support</strong>7 days a week</div>
        </div>
        <div class="fp-trust__item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
            <div><strong>Secure Payment</strong>ABA · ACLEDA · Cash</div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════ HERO SLIDER ══════════════════════════ -->
<section class="fp-hero" id="fp-hero">

    <div class="fp-slides" id="fp-slides">
        <?php foreach ( $slider_images as $img_url ) : ?>
        <div class="fp-slide">
            <img class="fp-slide__img" src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy" />
            <div class="fp-slide__overlay">
                <div class="fp-slide__content">
                    <div class="fp-hero__tag">New Arrivals 2025</div>
                    <h1 class="fp-hero__title">Your Ultimate<br><span>Tech Store</span></h1>
                    <p class="fp-hero__sub">Laptops · Gaming Gear · PC Hardware · Accessories<br>— all under one roof at the best prices.</p>
                    <a href="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>" class="fp-hero__cta">
                        Shop Now
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Prev arrow -->
    <button class="fp-arrow fp-arrow--prev" id="fp-prev" aria-label="Previous slide">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
    </button>

    <!-- Next arrow -->
    <button class="fp-arrow fp-arrow--next" id="fp-next" aria-label="Next slide">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
    </button>

    <!-- Dot indicators -->
    <div class="fp-dots" id="fp-dots">
        <?php for ( $d = 0; $d < $slide_count; $d++ ) : ?>
        <button class="fp-dot <?php echo $d === 0 ? 'active' : ''; ?>" data-index="<?php echo $d; ?>" aria-label="Slide <?php echo $d + 1; ?>"></button>
        <?php endfor; ?>
    </div>

</section>

<!-- ═══════════════════════════ CATEGORY NAV ═════════════════════════ -->
<nav class="fp-cats" aria-label="Product Categories">
    <div class="fp-cats__inner">

        <a href="<?php echo esc_url( get_term_link('laptop','product_cat') ); ?>" class="fp-cats__item">
            <svg class="fp-cats__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="13" rx="2"/>
                <path d="M2 19h20"/>
                <path d="M10 19l.5-2h3l.5 2"/>
                <path d="M10 11h4M10 8h2"/>
            </svg>
            Laptops
        </a>
        <a href="<?php echo esc_url( get_term_link('component','product_cat') ); ?>" class="fp-cats__item">
            <svg class="fp-cats__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="7" y="7" width="10" height="10" rx="1.5"/>
                <rect x="9.5" y="9.5" width="5" height="5" rx=".5"/>
                <path d="M9 4v3M12 4v3M15 4v3M9 17v3M12 17v3M15 17v3M4 9h3M4 12h3M4 15h3M17 9h3M17 12h3M17 15h3"/>
            </svg>
            PC Hardware
        </a>
        <a href="<?php echo esc_url( get_term_link('accessories','product_cat') ); ?>" class="fp-cats__item">
            <svg class="fp-cats__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 11a9 9 0 0 1 18 0"/>
                <path d="M3 11v2a2 2 0 0 0 2 2h1V10H5a2 2 0 0 0-2 1z"/>
                <path d="M21 11v2a2 2 0 0 1-2 2h-1V10h1a2 2 0 0 1 2 1z"/>
                <path d="M19 15v1a3 3 0 0 1-3 3h-2"/>
                <rect x="13" y="19" width="2" height="1" rx=".5"/>
            </svg>
            Accessories
        </a>
        <a href="<?php echo esc_url( get_term_link('gaming-gear','product_cat') ); ?>" class="fp-cats__item">
            <svg class="fp-cats__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 9H4a2 2 0 0 0-2 2v3a4 4 0 0 0 4 4h2l2 2h4l2-2h2a4 4 0 0 0 4-4v-3a2 2 0 0 0-2-2h-2a8 8 0 0 0-12 0z"/>
                <line x1="9" y1="11" x2="9" y2="15"/>
                <line x1="7" y1="13" x2="11" y2="13"/>
                <circle cx="15.5" cy="11.5" r=".75" fill="currentColor" stroke="none"/>
                <circle cx="17.5" cy="13.5" r=".75" fill="currentColor" stroke="none"/>
                <circle cx="15.5" cy="13.5" r=".75" fill="currentColor" stroke="none"/>
                <circle cx="17.5" cy="11.5" r=".75" fill="currentColor" stroke="none"/>
            </svg>
            Gaming Gear
        </a>
        <a href="<?php echo esc_url( get_term_link('monitor','product_cat') ); ?>" class="fp-cats__item">
            <svg class="fp-cats__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="13" rx="2"/>
                <path d="M12 16v4"/>
                <path d="M8 20h8"/>
                <path d="M7 8h3M7 11h2"/>
                <rect x="13" y="7" width="5" height="4" rx="1"/>
            </svg>
            Monitors
        </a>

    </div>
</nav>

<!-- ═══════════════════════════ MARVO SECTION ════════════════════════ -->
<style>
.fp-marvo {
    background: #fff;
    padding: 24px 5% 48px;
}
.fp-marvo__banner {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 28px;
    max-height: 220px;
}
.fp-marvo__banner img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    object-position: center;
    display: block;
}
.fp-marvo__banner-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(0,0,0,.65) 0%, rgba(0,0,0,.2) 60%, transparent 100%);
    display: flex;
    align-items: center;
    padding: 0 40px;
}
.fp-marvo__banner-text { color: #fff; }
.fp-marvo__banner-text h2 {
    font-size: clamp(22px, 3vw, 38px);
    font-weight: 800;
    margin: 0 0 6px;
    letter-spacing: -.5px;
}
.fp-marvo__banner-text p {
    font-size: 14px;
    opacity: .8;
    margin: 0 0 16px;
}
.fp-marvo__banner-text a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #13e800;
    color: #000;
    font-weight: 700;
    font-size: 13px;
    padding: 9px 22px;
    border-radius: 7px;
    text-decoration: none;
    transition: background .2s;
}
.fp-marvo__banner-text a:hover { background: #fff; text-decoration: none; }
</style>

<section class="fp-marvo">
    <div class="fp-marvo__banner">
        <img src="<?php echo esc_url( get_theme_mod( 'shopys_marvo_banner', shopys_marvo_banner_default() ) ); ?>" alt="Marvo Gaming Gear" />
        <div class="fp-marvo__banner-overlay">
            <div class="fp-marvo__banner-text">
                <h2>MARVO Gaming Gear</h2>
                <p>Keyboards · Mice · Headsets · Mousepads · Speakers</p>
                <a href="<?php echo esc_url( get_term_link('marvo','product_cat') ); ?>">
                    Shop Marvo
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
    <?php echo do_shortcode( '[premium_products category="marvo" limit="12" columns="6" filter="false" cart="true" show_description="false" pagination_type="normal"]' ); ?>
</section>

<!-- ═══════════════════════════ USED PRODUCTS ════════════════════════ -->
<section class="fp-products fp-used">
    <div class="fp-products__inner">
        <div class="fp-cat-section">
            <div class="fp-section-head">
                <div class="fp-section-head__left">
                    <div class="fp-section-head__bar" style="background:#f59e0b;"></div>
                    <div>
                        <h2 class="fp-section-head__title">Used Products <span style="background:#f59e0b;color:#000;font-size:12px;font-weight:700;padding:2px 8px;border-radius:20px;vertical-align:middle;margin-left:6px;">Second Hand</span></h2>
                        <span class="fp-section-head__count">Quality checked · Best price</span>
                    </div>
                </div>
                <?php
                $used_cat = get_term_by( 'slug', 'used-product', 'product_cat' );
                $used_link = $used_cat ? get_term_link( $used_cat ) : wc_get_page_permalink( 'shop' );
                ?>
                <a href="<?php echo esc_url( $used_link ); ?>" class="fp-section-head__link">
                    View All Used Products
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
            <?php echo do_shortcode( '[premium_products category="used-product" limit="12" columns="6" filter="false" cart="true" show_description="false" pagination_type="normal"]' ); ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════ PRODUCT SECTIONS ══════════════════════ -->
<section class="fp-products">
    <div class="fp-products__inner">

        <?php
        $fp_categories = array(
            array( 'slug' => 'laptop',           'label' => 'Laptops',      'count' => 93  ),
            array( 'slug' => 'component,desktop', 'label' => 'PC Hardware',  'count' => 249 ),
            array( 'slug' => 'accessories',       'label' => 'Accessories',  'count' => 69  ),
            array( 'slug' => 'gaming-gear',       'label' => 'Gaming Gear',  'count' => 80  ),
            array( 'slug' => 'monitor',           'label' => 'Monitors',     'count' => 37  ),
        );

        foreach ( $fp_categories as $cat ) :
            $first_slug = trim( explode( ',', $cat['slug'] )[0] );
            $cat_obj    = get_term_by( 'slug', $first_slug, 'product_cat' );
            $cat_link   = $cat_obj ? get_term_link( $cat_obj ) : wc_get_page_permalink( 'shop' );
        ?>
        <div class="fp-cat-section">
            <div class="fp-section-head">
                <div class="fp-section-head__left">
                    <div class="fp-section-head__bar"></div>
                    <div>
                        <h2 class="fp-section-head__title"><?php echo esc_html( $cat['label'] ); ?></h2>
                        <span class="fp-section-head__count"><?php echo esc_html( $cat['count'] ); ?>+ products available</span>
                    </div>
                </div>
                <a href="<?php echo esc_url( $cat_link ); ?>" class="fp-section-head__link">
                    View All <?php echo esc_html( $cat['label'] ); ?>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
            <?php echo do_shortcode( '[premium_products category="' . esc_attr( $cat['slug'] ) . '" limit="12" columns="6" filter="false" cart="true" show_description="false" pagination_type="normal"]' ); ?>
        </div>
        <?php endforeach; ?>

    </div>
</section>

<!-- ═══════════════════════════ SLIDER SCRIPT ════════════════════════ -->
<script>
(function () {
    var track  = document.getElementById('fp-slides');
    var dots   = document.querySelectorAll('.fp-dot');
    var total  = <?php echo (int) $slide_count; ?>;
    var cur    = 0;
    var timer;

    function goTo(n) {
        cur = (n + total) % total;
        track.style.transform = 'translateX(-' + (cur * 100) + '%)';
        dots.forEach(function (d, i) { d.classList.toggle('active', i === cur); });
    }

    function next() { goTo(cur + 1); }
    function prev() { goTo(cur - 1); }

    function startAuto() { timer = setInterval(next, 5000); }
    function stopAuto()  { clearInterval(timer); }

    document.getElementById('fp-next').addEventListener('click', function () { stopAuto(); next(); startAuto(); });
    document.getElementById('fp-prev').addEventListener('click', function () { stopAuto(); prev(); startAuto(); });

    dots.forEach(function (d) {
        d.addEventListener('click', function () {
            stopAuto();
            goTo(parseInt(d.dataset.index, 10));
            startAuto();
        });
    });

    startAuto();
})();
</script>

<!-- ═══════════════════════════ BRAND SLIDER ═════════════════════════ -->
<style>
.fp-brands {
    background: #fff;
    padding: 40px 5% 44px;
    border-top: 1px solid #eaeaea;
}
.fp-brands__title {
    text-align: center;
    font-size: 20px;
    font-weight: 800;
    color: #111827;
    margin: 0 0 28px;
    letter-spacing: -.2px;
}
.fp-brands__slider {
    position: relative;
    overflow: hidden;
}
.fp-brands__track {
    display: flex;
    align-items: center;
    transition: transform .55s cubic-bezier(.77,0,.18,1);
    will-change: transform;
}
.fp-brands__item {
    flex: 0 0 calc(100% / 7);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px 20px;
}
.fp-brands__item img {
    max-height: 52px;
    max-width: 120px;
    width: auto;
    object-fit: contain;
    transition: transform .25s;
}
.fp-brands__item img:hover {
    transform: scale(1.08);
}
.fp-brands__arrow {
    position: absolute;
    top: 50%; transform: translateY(-50%);
    z-index: 5;
    background: #fff;
    border: 1.5px solid #e2e6ea;
    color: #444;
    width: 36px; height: 36px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all .2s;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
}
.fp-brands__arrow:hover { background: #13e800; border-color: #13e800; color: #000; }
.fp-brands__arrow--prev { left: 0; }
.fp-brands__arrow--next { right: 0; }
.fp-brands__dots {
    display: flex;
    justify-content: center;
    gap: 7px;
    margin-top: 20px;
}
.fp-brands__dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #d1d5db;
    border: none; cursor: pointer;
    transition: background .2s, transform .2s;
    padding: 0;
}
.fp-brands__dot.active { background: #13e800; transform: scale(1.35); }
@media (max-width: 768px) {
    .fp-brands__item { flex: 0 0 calc(100% / 3); }
}
</style>

<section class="fp-brands">
    <h2 class="fp-brands__title">Brand We Distribute</h2>
    <div class="fp-brands__slider" id="fp-brands-slider">
        <div class="fp-brands__track" id="fp-brands-track">
            <?php
            $_b = untrailingslashit( home_url() );
            $fp_brands = array(
                array( 'name' => 'Dell',      'url' => $_b . '/wp-content/uploads/2023/01/Dell_logo_2016.svg.png' ),
                array( 'name' => 'MSI',       'url' => $_b . '/wp-content/uploads/2023/01/MSI-Logo.png' ),
                array( 'name' => 'Predator',  'url' => $_b . '/wp-content/uploads/2023/01/Acer-Predator-logo.jpeg' ),
                array( 'name' => 'Acer',      'url' => $_b . '/wp-content/uploads/2023/01/acer-2011.svg' ),
                array( 'name' => 'Alienware', 'url' => $_b . '/wp-content/uploads/2023/01/Alienware-Logo.png' ),
                array( 'name' => 'Apple',     'url' => $_b . '/wp-content/uploads/2023/01/Apple-Logo.png' ),
                array( 'name' => 'Huawei',    'url' => $_b . '/wp-content/uploads/2023/01/Huawei-Logo.png' ),
                array( 'name' => 'HP',        'url' => $_b . '/wp-content/uploads/2023/01/2048px-HP_logo_2012.svg.png' ),
                array( 'name' => 'Lenovo',    'url' => $_b . '/wp-content/uploads/2023/01/Lenovo-Logo.jpg' ),
                array( 'name' => 'Logitech',  'url' => $_b . '/wp-content/uploads/2023/01/Logitech-Logo.png' ),
                array( 'name' => 'Samsung',   'url' => $_b . '/wp-content/uploads/2023/01/Samsung-Logo-2.png' ),
                array( 'name' => 'Razer',     'url' => $_b . '/wp-content/uploads/2023/01/Razer_snake_logo.svg.png' ),
                array( 'name' => 'ASUS ROG',  'url' => $_b . '/wp-content/uploads/2025/12/asus-r-o-g-logo-mo786jhtsvw0537d-mo786jhtsvw0537d.jpg' ),
                array( 'name' => 'DXRacer',   'url' => $_b . '/wp-content/uploads/2025/12/1687852557206-1676343536644-DXRacer-Logo.png' ),
            );
            foreach ( $fp_brands as $brand ) : ?>
            <div class="fp-brands__item">
                <img src="<?php echo esc_url( $brand['url'] ); ?>" alt="<?php echo esc_attr( $brand['name'] ); ?>" loading="lazy" />
            </div>
            <?php endforeach; ?>
        </div>

        <button class="fp-brands__arrow fp-brands__arrow--prev" id="fp-brands-prev" aria-label="Previous">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button class="fp-brands__arrow fp-brands__arrow--next" id="fp-brands-next" aria-label="Next">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
        </button>
    </div>

    <div class="fp-brands__dots" id="fp-brands-dots"></div>
</section>

<script>
(function () {
    var track     = document.getElementById('fp-brands-track');
    var dotsWrap  = document.getElementById('fp-brands-dots');
    var perView   = window.innerWidth <= 768 ? 3 : 7;
    var items     = track.querySelectorAll('.fp-brands__item');
    var total     = items.length;
    var pages     = Math.ceil(total / perView);
    var cur       = 0;
    var timer;

    // Build dots
    for (var i = 0; i < pages; i++) {
        var d = document.createElement('button');
        d.className = 'fp-brands__dot' + (i === 0 ? ' active' : '');
        d.dataset.i = i;
        d.setAttribute('aria-label', 'Page ' + (i + 1));
        dotsWrap.appendChild(d);
    }

    function goTo(n) {
        cur = (n + pages) % pages;
        var pct = cur * (perView / total) * 100;
        track.style.transform = 'translateX(-' + pct + '%)';
        dotsWrap.querySelectorAll('.fp-brands__dot').forEach(function (d, i) {
            d.classList.toggle('active', i === cur);
        });
    }

    document.getElementById('fp-brands-next').addEventListener('click', function () { clearInterval(timer); goTo(cur + 1); startAuto(); });
    document.getElementById('fp-brands-prev').addEventListener('click', function () { clearInterval(timer); goTo(cur - 1); startAuto(); });
    dotsWrap.addEventListener('click', function (e) {
        if (e.target.classList.contains('fp-brands__dot')) {
            clearInterval(timer); goTo(parseInt(e.target.dataset.i)); startAuto();
        }
    });

    function startAuto() { timer = setInterval(function () { goTo(cur + 1); }, 3000); }
    startAuto();
})();
</script>

<?php get_footer(); ?>
