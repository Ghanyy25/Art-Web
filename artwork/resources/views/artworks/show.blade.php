<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $artwork->title }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:flex md:space-x-8 md:items-start">

                    {{-- Bagian Kiri: Gambar --}}
                    <div class="w-full md:w-2/3 mb-6 md:mb-0 flex justify-center items-center bg-gray-100 rounded-lg p-2 sticky top-6 self-start">
                        <img src="{{ Storage::url($artwork->file_path) }}"
                             alt="{{ $artwork->title }}"
                             class="max-w-full max-h-[85vh] w-auto h-auto object-contain rounded-lg shadow-md">
                    </div>

                    {{-- Bagian Kanan: Detail & Interaksi --}}
                    <div class="w-full md:w-1/3">

                        {{-- Info User --}}
                        <div class="flex items-center mb-4">
                            <a href="{{ route('profile.show', $artwork->user->id) }}">
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="{{ $artwork->user->profile_picture ? Storage::url($artwork->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($artwork->user->name) }}"
                                     alt="{{ $artwork->user->name }}">
                            </a>
                            <div class="ml-3">
                                <a href="{{ route('profile.show', $artwork->user->id) }}" class="text-sm font-bold text-gray-900 hover:underline hover:text-indigo-600">
                                    {{ $artwork->user->name }}
                                </a>
                                <span class="text-xs text-gray-500 block">Kreator</span>
                            </div>
                        </div>

                        {{-- Deskripsi (Expandable) --}}
                        <div x-data="{ expanded: false }" class="mb-4">
                            <p class="text-sm text-gray-700 mb-1 whitespace-pre-wrap break-words transition-all duration-300"
                                :class="expanded ? '' : 'line-clamp-4'">
                                {{ $artwork->description }}
                            </p>
                            @if(Str::length($artwork->description) > 150)
                                <button @click="expanded = !expanded" class="text-indigo-600 text-xs font-bold hover:underline focus:outline-none">
                                    <span x-show="!expanded">Lihat Selengkapnya</span>
                                    <span x-show="expanded">Sembunyikan</span>
                                </button>
                            @endif
                        </div>

                        {{-- Kategori & Tags --}}
                        <div class="mb-6">
                            <span class="inline-block bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1 rounded-full border border-gray-200">
                                {{ $artwork->category->name ?? 'Uncategorized' }}
                            </span>
                            @if($artwork->tags)
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach(explode(',', $artwork->tags) as $tag)
                                        <span class="inline-block bg-indigo-50 text-indigo-600 text-xs font-medium px-2 py-1 rounded-md">
                                            #{{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Interaksi (Like, Save, Report) --}}
                        @auth
                            @php
                                $isLiked = auth()->check() && $artwork->likes->contains('user_id', auth()->id());
                                $isSaved = auth()->check() && $artwork->favorites->contains('user_id', auth()->id());
                            @endphp

                            <div class="flex items-center justify-between mb-6 pt-4 border-t border-gray-100">
                                <div class="flex space-x-4">
                                    {{-- Like Button --}}
                                    <button onclick="toggleLike({{ $artwork->id }})" id="like-btn-{{ $artwork->id }}"
                                        class="group flex items-center space-x-1 focus:outline-none transition {{ $isLiked ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">
                                        <svg id="like-icon-{{ $artwork->id }}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform group-active:scale-125 {{ $isLiked ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span id="like-count-{{ $artwork->id }}" class="text-sm font-semibold">{{ $artwork->likes->count() }}</span>
                                    </button>

                                    {{-- Favorite Button --}}
                                    <button onclick="toggleFavorite({{ $artwork->id }})" id="fav-btn-{{ $artwork->id }}"
                                        class="group flex items-center space-x-1 focus:outline-none transition {{ $isSaved ? 'text-yellow-500' : 'text-gray-500 hover:text-yellow-500' }}">
                                        <svg id="fav-icon-{{ $artwork->id }}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform group-active:scale-125 {{ $isSaved ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Report Artwork Button --}}
                                <button onclick="openReportModal('artwork', {{ $artwork->id }})" class="text-gray-400 hover:text-red-600 transition" title="Laporkan Karya">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-8a2 2 0 01-2-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v17" />
                                    </svg>
                                </button>
                            </div>
                        @endauth

                        @guest
                            <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-lg text-sm text-center text-indigo-800 mb-6">
                                <a href="{{ route('login') }}" class="font-bold hover:underline">Login</a> untuk memberi like & komentar.
                            </div>
                        @endguest

                    </div>
                </div>

                {{-- Bagian Komentar --}}
                <div class="border-t border-gray-200 px-6 py-8 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Komentar <span class="text-gray-500 font-normal text-sm">({{ $artwork->comments->count() }})</span></h3>

                    {{-- 1. FORM INPUT KOMENTAR (DIPINDAHKAN KE ATAS) --}}
                    @auth
                        <div class="mb-8 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                            <form id="comment-form" onsubmit="submitComment(event, {{ $artwork->id }})" class="relative flex items-start space-x-4">
                                <img src="{{ auth()->user()->profile_picture ? Storage::url(auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                     alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full border border-gray-200 flex-shrink-0">
                                <div class="w-full">
                                    <textarea
                                        id="comment-body"
                                        rows="2"
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-3 text-sm resize-none"
                                        placeholder="Tulis pendapatmu tentang karya ini..."
                                        required></textarea>

                                    <div class="mt-2 text-right">
                                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                                            Kirim Komentar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endauth

                    {{-- 2. LIST KOMENTAR (SCROLLABLE) --}}
                    {{-- max-h-[600px] overflow-y-auto: Membuat area scroll jika komentar panjang --}}
                    <div id="comments-container" class="max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                        <div id="comments-list" class="space-y-6">
                            @forelse($artwork->comments->sortByDesc('created_at') as $comment)
                                {{-- Gunakan sortByDesc agar komentar terbaru di atas (sesuai posisi form) --}}
                                <div id="comment-{{ $comment->id }}" class="flex items-start space-x-4 group">

                                    {{-- Avatar User --}}
                                    <a href="{{ route('profile.show', $comment->user->id) }}">
                                        <img class="w-10 h-10 rounded-full object-cover border border-gray-200 flex-shrink-0"
                                        src="{{ $comment->user->profile_picture ? Storage::url($comment->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                                        alt="{{ $comment->user->name }}">
                                    </a>

                                    <div class="flex-1">
                                        <div class="bg-white border border-gray-200 p-4 rounded-lg rounded-tl-none relative shadow-sm">

                                            <div class="flex justify-between items-start mb-1">
                                                <div>
                                                    <a href="{{ route('profile.show', $comment->user->id) }}" class="font-bold text-gray-900 text-sm hover:underline">
                                                        {{ $comment->user->name }}
                                                    </a>
                                                    <span class="text-xs text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>

                                                {{-- Menu Opsi (Titik Tiga) --}}
                                                @auth
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" class="text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded hover:bg-gray-100 transition">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                            </svg>
                                                        </button>

                                                        <div x-show="open" @click.away="open = false"
                                                             x-transition:enter="transition ease-out duration-100"
                                                             x-transition:enter-start="transform opacity-0 scale-95"
                                                             x-transition:enter-end="transform opacity-100 scale-100"
                                                             class="absolute right-0 mt-1 w-32 bg-white rounded-md shadow-lg z-50 border border-gray-100 py-1 origin-top-right"
                                                             style="display: none;">

                                                            @if(Auth::id() === $comment->user_id)
                                                                <button onclick="deleteComment({{ $comment->id }})"
                                                                        class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition flex items-center">
                                                                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                    Hapus
                                                                </button>
                                                            @else
                                                                <button onclick="openReportModal('comment', {{ $comment->id }})"
                                                                        class="w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition flex items-center">
                                                                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-8a2 2 0 01-2-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H3z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v17"></path></svg>
                                                                    Laporkan
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endauth
                                            </div>

                                            <p class="text-gray-800 text-sm whitespace-pre-wrap break-words leading-relaxed">{{ $comment->body }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8" id="no-comments-text">
                                    <div class="bg-gray-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">Belum ada komentar.</p>
                                    <p class="text-gray-400 text-xs mt-1">Jadilah yang pertama berpendapat!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL REPORT (Universal) --}}
    <div id="report-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm px-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100" @click.away="closeReportModal()">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900" id="report-modal-title">Laporkan Konten</h3>
                <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6">
                <form id="report-form" onsubmit="submitReport(event)">
                    @csrf

                    {{-- INPUT HIDDEN --}}
                    <input type="hidden" id="report-type" name="type">
                    <input type="hidden" id="report-target-id" name="target_id">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pelaporan</label>
                        <textarea id="report-reason" name="reason" rows="4"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                  placeholder="Jelaskan alasan pelaporan..." required></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeReportModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 shadow-sm transition">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT --}}
    <script>
        // --- 1. FITUR LIKE ---
        function toggleLike(artworkId) {
            axios.post('/like/artwork/' + artworkId)
                .then(function (response) {
                    const data = response.data;
                    const countSpan = document.getElementById('like-count-' + artworkId);
                    const btn = document.getElementById('like-btn-' + artworkId);
                    const icon = document.getElementById('like-icon-' + artworkId);

                    countSpan.innerText = data.likes_count;

                    if (data.is_liked) {
                        btn.classList.remove('text-gray-500'); btn.classList.add('text-red-500');
                        icon.classList.remove('fill-none'); icon.classList.add('fill-current');
                    } else {
                        btn.classList.remove('text-red-500'); btn.classList.add('text-gray-500');
                        icon.classList.remove('fill-current'); icon.classList.add('fill-none');
                    }
                })
                .catch(err => {
                    if(err.response?.status === 401) window.location.href = '/login';
                });
        }

        // --- 2. FITUR FAVORITE ---
        function toggleFavorite(artworkId) {
            axios.post('/favorite/artwork/' + artworkId)
                .then(function (response) {
                    const data = response.data;
                    const btn = document.getElementById('fav-btn-' + artworkId);
                    const icon = document.getElementById('fav-icon-' + artworkId);

                    if (data.is_favorited) {
                        btn.classList.remove('text-gray-500'); btn.classList.add('text-yellow-500');
                        icon.classList.remove('fill-none'); icon.classList.add('fill-current');
                    } else {
                        btn.classList.remove('text-yellow-500'); btn.classList.add('text-gray-500');
                        icon.classList.remove('fill-current'); icon.classList.add('fill-none');
                    }
                })
                .catch(err => {
                    if(err.response?.status === 401) window.location.href = '/login';
                });
        }

        // --- 3. FITUR KIRIM KOMENTAR ---
        // --- 3. FITUR KIRIM KOMENTAR ---
        function submitComment(event, artworkId) {
            event.preventDefault();
            const bodyInput = document.getElementById('comment-body');
            const noCommentsText = document.getElementById('no-comments-text');
            const submitBtn = event.target.querySelector('button[type="submit"]');

            if (!bodyInput.value.trim()) return;

            // Disable button biar gak double submit
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            axios.post('/comment/artwork/' + artworkId, { body: bodyInput.value })
            .then(function (response) {
                const data = response.data;
                const newComment = data.data;

                if (noCommentsText) noCommentsText.remove();

                const commentHtml = `
                    <div id="comment-${newComment.id}" class="flex items-start space-x-4 animate-fade-in-down group">
                        <img src="${data.user_avatar}" class="w-10 h-10 object-cover rounded-full border border-gray-200">

                        <div class="flex-1">
                            <div class="bg-white border border-gray-200 p-4 rounded-lg rounded-tl-none w-full relative shadow-sm">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="font-bold text-gray-900 text-sm">${data.user_name}</h4>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500">Baru saja</span>
                                        <button onclick="deleteComment(${newComment.id})" class="text-red-400 hover:text-red-600 text-xs ml-2" title="Hapus">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-gray-800 text-sm whitespace-pre-wrap break-words">${newComment.body}</p>
                            </div>
                        </div>
                    </div>
                `;

                // PERUBAHAN PENTING: Pakai 'afterbegin' agar muncul di PALING ATAS
                document.getElementById('comments-list').insertAdjacentHTML('afterbegin', commentHtml);

                // Scroll container ke paling atas agar user melihat komentarnya
                document.getElementById('comments-container').scrollTop = 0;

                bodyInput.value = '';
            })
            .catch(err => alert('Gagal mengirim komentar.'))
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }

        // --- 4. FITUR HAPUS KOMENTAR ---
        function deleteComment(commentId) {
            if(!confirm("Hapus komentar ini?")) return;

            axios.delete('/comment/' + commentId)
                .then(function(response) {
                    const el = document.getElementById('comment-' + commentId);
                    if(el) {
                        el.remove();
                        const list = document.getElementById('comments-list');
                        if(list.children.length === 0) {
                            list.innerHTML = '<div class="text-center py-8" id="no-comments-text"><p class="text-gray-400 italic text-sm">Belum ada komentar.</p></div>';
                        }
                    }
                })
                .catch(err => alert('Gagal menghapus komentar.'));
        }

        // --- 5. LOGIKA MODAL REPORT ---
        function openReportModal(type, id) {
            // Debugging: Cek data yang masuk saat tombol diklik
            console.log("Open Modal:", type, id);

            const modal = document.getElementById('report-modal');
            const title = document.getElementById('report-modal-title');

            // Set nilai ke input hidden
            document.getElementById('report-type').value = type;
            document.getElementById('report-target-id').value = id;
            document.getElementById('report-reason').value = ''; // Reset textarea

            // Ubah Judul Modal sesuai tipe
            if (title) {
                title.innerText = (type === 'artwork') ? "Laporkan Karya Ini" : "Laporkan Komentar Ini";
            }

            // Tampilkan Modal
            if (modal) modal.classList.remove('hidden');
        }

        function closeReportModal() {
            const modal = document.getElementById('report-modal');
            if (modal) modal.classList.add('hidden');
        }

        // --- FUNGSI SUBMIT LAPORAN ---
        function submitReport(event) {
            event.preventDefault();

            // Ambil nilai dari input hidden
            const type = document.getElementById('report-type').value;
            const id = document.getElementById('report-target-id').value;
            const reason = document.getElementById('report-reason').value;

            console.log("Submitting Report:", { type, id, reason });

            // Siapkan Payload Data JSON
            let payload = {
                reason: reason
            };

            // Logika Pengisian ID berdasarkan Tipe
            if (type === 'artwork') {
                payload.artwork_id = id;
                // Pastikan comment_id null agar tidak error validasi
                payload.comment_id = null;
            } else if (type === 'comment') {
                payload.comment_id = id;
                // Pastikan artwork_id null
                payload.artwork_id = null;
            }

            // Kirim Request ke Server
            axios.post('/report/store', payload)
                .then(function (response) {
                    // Sukses
                    alert(response.data.message);
                    closeReportModal();
                })
                .catch(function (error) {
                    // Error Handling
                    console.error("Report Error:", error);

                    let msg = "Gagal mengirim laporan.";
                    if (error.response) {
                        if (error.response.status === 401) {
                            msg = "Silakan login terlebih dahulu.";
                            window.location.href = '/login';
                        } else if (error.response.data && error.response.data.message) {
                            msg += "\n" + error.response.data.message;
                        }
                    }
                    alert(msg);
                });
        }
    </script>
</x-app-layout>
