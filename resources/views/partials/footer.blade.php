@php
    $year = now()->year;
    $socials = [
        ['label' => 'واتساپ', 'href' => 'https://api.whatsapp.com/send?phone=4917647164642', 'icon' => 'wa'],
        ['label' => 'اینستاگرام', 'href' => 'https://www.instagram.com/germanmode159/', 'icon' => 'ig'],
        ['label' => 'تلگرام', 'href' => 'https://web.telegram.org/a/#-1001195435219', 'icon' => 'tg'],
        ['label' => 'Threads', 'href' => 'https://www.threads.net/@germanmode159', 'icon' => 'th'],
        ['label' => 'یوتیوب', 'href' => 'https://www.youtube.com/@جرمنیمید', 'icon' => 'yt'],
        ['label' => 'آپارات', 'href' => 'https://www.aparat.com/germanymode', 'icon' => 'ap'],
        ['label' => 'نماشا', 'href' => 'https://www.namasha.com/channel7119557632', 'icon' => 'nm'],
    ];
@endphp

<footer class="ork-footer">
    <div class="ork-footer-glow" aria-hidden="true"></div>
    <div class="ork-footer-inner">
        <div class="ork-footer-brand">
            <a class="ork-footer-logo" href="{{ route('shop.home') }}">
                <span class="ork-footer-logo-glass">
                    <img class="ork-footer-logo-img" src="{{ asset('images/logo-germany-mode.png') }}?v=4" alt="جرمنی مد | Germany mode" width="148" height="82" loading="lazy">
                </span>
                <span>
                    <strong>جرمنی مد</strong>
                    <small>Germanymode</small>
                </span>
            </a>
            <p>
                فروشگاه اینترنتی خرید مستقیم از آلمان و اروپا — کالای اورجینال، قیمت شفاف و ارسال تا درب منزل.
            </p>
            <div class="ork-footer-social">
                @foreach($socials as $social)
                    <a href="{{ $social['href'] }}" target="_blank" rel="noopener noreferrer" title="{{ $social['label'] }}" aria-label="{{ $social['label'] }}">
                        @if($social['icon'] === 'wa')
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M20.5 3.5A11.8 11.8 0 0 0 12.05 0C5.5 0 .2 5.3.2 11.85c0 2.1.55 4.1 1.6 5.9L0 24l6.4-1.7a11.8 11.8 0 0 0 5.65 1.45h.05c6.55 0 11.85-5.3 11.85-11.85 0-3.17-1.23-6.15-3.45-8.4zm-8.45 18.2h-.04a9.8 9.8 0 0 1-5-1.37l-.36-.21-3.8 1 1.02-3.7-.24-.38a9.8 9.8 0 0 1-1.5-5.22c0-5.42 4.42-9.83 9.86-9.83a9.78 9.78 0 0 1 9.83 9.84c0 5.43-4.42 9.87-9.77 9.87zm5.4-7.37c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.64.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.64-2.04-.17-.3-.02-.46.13-.6.13-.13.3-.35.44-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5h-.57c-.2 0-.52.07-.8.37-.27.3-1.05 1.02-1.05 2.5s1.08 2.9 1.23 3.1c.15.2 2.12 3.24 5.14 4.54 1.9.82 2.65.9 3.6.76.55-.08 1.75-.72 2-1.4.25-.7.25-1.28.17-1.4-.07-.12-.27-.2-.57-.35z"/></svg>
                        @elseif($social['icon'] === 'ig')
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                        @elseif($social['icon'] === 'tg')
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M21.8 3.3 2.9 10.5c-1.3.5-1.3 1.2-.2 1.5l4.8 1.5 1.8 5.6c.2.7.4 1 .9 1 .5 0 .7-.2 1-1l2.3-3.7 4.7 3.5c.9.5 1.5.2 1.7-.8l3.1-14.5c.3-1.3-.5-1.9-1.5-1.5zM8.9 13.7l8.9-5.6c.4-.3.8 0 .5.3l-7.3 6.6-.3 3.2-1.8-4.5z"/></svg>
                        @elseif($social['icon'] === 'th')
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M16.3 7.2c-.6-.4-1.4-.7-2.3-.8V4h-2.2v10.3c0 1.2-.9 2.1-2.1 2.1s-2.1-.9-2.1-2.1.9-2.1 2.1-2.1c.2 0 .4 0 .6.1V10c-.2 0-.4-.1-.6-.1-2.4 0-4.3 1.9-4.3 4.3S7.3 18.5 9.7 18.5s4.3-1.9 4.3-4.3V9.8c.8.6 1.8 1 2.9 1.1V8.7c-.3 0-.5-.1-.6-.2z"/></svg>
                        @elseif($social['icon'] === 'yt')
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M23 12.2s0-3.2-.4-4.6c-.2-.9-.9-1.6-1.8-1.8C19.4 5.4 12 5.4 12 5.4s-7.4 0-8.8.4c-.9.2-1.6.9-1.8 1.8C1 9 1 12.2 1 12.2s0 3.2.4 4.6c.2.9.9 1.6 1.8 1.8 1.4.4 8.8.4 8.8.4s7.4 0 8.8-.4c.9-.2 1.6-.9 1.8-1.8.4-1.4.4-4.6.4-4.6zM9.8 15.3V9.1l6.1 3.1-6.1 3.1z"/></svg>
                        @elseif($social['icon'] === 'ap')
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm-1.2 5.5h2.4v5.2l3.6 2.1-1.2 2-4.8-2.8V7.5z"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="M10 9l5 3-5 3V9z"/></svg>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <div class="ork-footer-col">
            <h3>دسترسی سریع</h3>
            <ul>
                <li><a href="{{ route('shop.home') }}">خانه</a></li>
                <li><a href="{{ route('shop.search') }}">همه کالاها</a></li>
                <li><a href="{{ route('shop.search') }}">دسته‌بندی محصولات</a></li>
                <li><a href="https://germanymode.com/" target="_blank" rel="noopener noreferrer">وب‌سایت اصلی</a></li>
            </ul>
        </div>

        <div class="ork-footer-col">
            <h3>دفاتر</h3>
            <div class="ork-footer-office">
                <strong>دفتر فروش مرکزی — آلمان</strong>
                <p>Deutschland — 54296 Trier, Am Weidengraben 90</p>
                <p>آلمان، راینلند-فالتس، ترییر — روبروی دانشگاه ترییر، پلاک ۹۰</p>
            </div>
            <div class="ork-footer-office">
                <strong>دفتر پشتیبانی — تهران</strong>
                <p>تهران، خیابان امام خمینی، کوچه شهید میرزایی، پاساژ حافظ، طبقه اول، پلاک ۲۲</p>
            </div>
        </div>

        <div class="ork-footer-col">
            <h3>تماس با ما</h3>
            <ul class="ork-footer-contacts">
                <li>
                    <span>حمیدرضا جنیدی (آلمان)</span>
                    <a href="tel:+4917647164642" dir="ltr">+49 176 47164642</a>
                    <small>واتساپ / ایمو</small>
                </li>
                <li>
                    <span>حسن جنیدی (تهران)</span>
                    <a href="tel:+982166709169" dir="ltr">021-66709169</a>
                </li>
                <li>
                    <span>موبایل پشتیبانی</span>
                    <a href="tel:+989191200822" dir="ltr">0919 120 0822</a>
                </li>
                <li>
                    <span>ایمیل</span>
                    <a href="mailto:info@germanymode.com" dir="ltr">info@germanymode.com</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="ork-footer-bottom">
        <div class="ork-footer-bottom-inner">
            <p>© {{ $year }} فروشگاه اینترنتی جرمنی مد — تمامی حقوق محفوظ است.</p>
            <p class="ork-footer-note">خرید مستقیم از آلمان و اروپا با ضمانت اصالت کالا</p>
        </div>
    </div>
</footer>
