// resources/js/bootstrap.js

import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// ====== Tambahkan dari sini (Pusher + Echo) ======
import Echo from "laravel-echo";
import Pusher from "pusher-js";

// simpan CSRF token dari <meta name="csrf-token" ...> (Breeze biasanya sudah ada di <head>)
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute("content");

// wajib: expose Pusher ke window (dibutuhkan Echo)
window.Pusher = Pusher;

// inisialisasi Echo pakai env Vite (pastikan VITE_PUSHER_* sudah ada di .env)
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY ?? process.env.MIX_PUSHER_APP_KEY,
    cluster:
        import.meta.env.VITE_PUSHER_APP_CLUSTER ??
        process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? window.location.hostname,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001, // kalau pakai laravel-websockets; kalau Pusher cloud biarin default
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: true,
    enabledTransports: ["ws", "wss"],
    csrfToken: document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content"),
});
// ====== Sampai sini ======
