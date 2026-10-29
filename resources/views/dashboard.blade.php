
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Reverb
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-3">
                    Pengujian Event Broadcast
                </h3>

                <p class="text-gray-600 mb-5">
                    Klik tombol berikut untuk memicu event OrderPlaced
                    dan menguji notifikasi Laravel Reverb.
                </p>

                <button
                    id="test-order-btn"
                    type="button"
                    class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-5 py-3 rounded-lg"
                >
                    Tes Notifikasi
                </button>

                <p id="test-order-result" class="mt-4 text-gray-700">
                    Menyiapkan koneksi Reverb...
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const result = document.getElementById('test-order-result');
            const button = document.getElementById('test-order-btn');

            if (!window.Echo) {
                result.textContent = 'Gagal: Laravel Echo belum tersedia.';
                console.error('Laravel Echo belum tersedia.');
                return;
            }

            // Listener untuk menerima event OrderPlaced
            window.Echo
                .private('user.{{ auth()->id() }}')
                .listen('.order.placed', (e) => {
                    console.log('EVENT DITERIMA:', e);

                    alert(
                        `Order #${e.order_id} telah ditempatkan! Total: Rp ${e.total}`
                    );
                });

            result.textContent = 'Echo aktif. Siap melakukan pengujian.';

            // Tombol untuk memicu event
            button.addEventListener('click', async function () {
                button.disabled = true;
                result.textContent = 'Sedang mengirim event...';

                try {
                    const csrfToken = document.querySelector(
                        'meta[name="csrf-token"]'
                    );

                    if (!csrfToken) {
                        throw new Error('Token CSRF tidak ditemukan.');
                    }

                    const response = await fetch('/test-order', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            data.message || 'HTTP ' + response.status
                        );
                    }

                    result.textContent =
                        'Trigger berhasil dipanggil! Order #' + data.order_id;

                } catch (error) {
                    result.textContent = 'Gagal: ' + error.message;
                    console.error(error);
                } finally {
                    button.disabled = false;
                }
            });
        });
    </script>
</x-app-layout>
