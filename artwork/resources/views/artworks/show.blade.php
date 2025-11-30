<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $artwork->title }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:flex md:space-x-8 md:items-start">

                    <div class="w-full md:w-2/3 mb-6 md:mb-0 flex justify-center items-center bg-gray-100 rounded-lg p-2">
                        <img src="{{ Storage::url($artwork->file_path) }}"
                            alt="{{ $artwork->title }}"
                            class="max-w-full max-h-[85vh] w-auto h-auto object-contain rounded-lg shadow-md">
                    </div>

                    <div class="w-full md:w-1/3">

                        <div class="flex items-center mb-4">
                            <a href="{{ route('profile.show', $artwork->user->id) }}">
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="{{ $artwork->user->profile_picture ? Storage::url($artwork->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($artwork->user->name) }}"
                                     alt="{{ $artwork->user->name }}">
                            </a>
                            <div class="ml-3">
                                <a href="{{ route('profile.show', $artwork->user->id) }}" class="text-sm font-medium text-gray-900 hover:underline">
                                    {{ $artwork->user->name }}
                                </a>
                                <span class="text-sm text-gray-500 block">Kreator</span>
                            </div>
                        </div>

                        <div x-data="{ expanded: false }">

                            <p
                                class="text-sm text-gray-700 mb-2 whitespace-pre-wrap break-words transition-all duration-300"
                                :class="expanded ? '' : 'line-clamp-6'"
                            >
                                {{ $artwork->description }}
                            </p>

                            {{-- Tampilkan tombol hanya jika deskripsi cukup panjang --}}
                            @if(Str::length($artwork->description) > 150)
                                <button
                                    @click="expanded = !expanded"
                                    class="text-blue-600 text-xs font-bold hover:underline focus:outline-none"
                                >
                                    <span x-show="!expanded">Lihat Selengkapnya</span>
                                    <span x-show="expanded">Sembunyikan</span>
                                </button>
                            @endif

                        </div>

                        <div class="mb-6">
                            <span class="inline-block bg-gray-100 text-gray-700 text-xs font-medium px-3 py-1 rounded-full">
                                {{ $artwork->category->name ?? null }}
                            </span>
                            @if($artwork->tags)
                                <div class="mt-2 space-x-2">
                                    @foreach(explode(',', $artwork->tags) as $tag)
                                        <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-medium px-2 py-1 rounded-full">
                                            #{{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @auth
                        @php
                            // Cek status Like & Save untuk User yang sedang login
                            $isLiked = auth()->check() && $artwork->likes->contains('user_id', auth()->id());
                            $isSaved = auth()->check() && $artwork->favorites->contains('user_id', auth()->id());
                        @endphp
                            <div class="flex space-x-2 mb-6">
                                <button
                                    onclick="toggleLike({{ $artwork->id }})"
                                    id="like-btn-{{ $artwork->id }}"
                                    class="group flex items-center space-x-2 focus:outline-none transition duration-150 ease-in-out {{ $isLiked ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }}">

                                    <svg id="like-icon-{{ $artwork->id }}" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition-transform group-active:scale-110 {{ $isLiked ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>

                                    <span id="like-count-{{ $artwork->id }}" class="text-lg font-semibold">
                                        {{ $artwork->likes->count() }}
                                    </span>
                                </button>

                                <button
                                    onclick="toggleFavorite({{ $artwork->id }})"
                                    id="fav-btn-{{ $artwork->id }}"
                                    class="group flex items-center space-x-2 focus:outline-none transition duration-150 ease-in-out {{ $isSaved ? 'text-yellow-500' : 'text-gray-500 hover:text-yellow-500' }}">

                                    <svg id="fav-icon-{{ $artwork->id }}" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transition-transform group-active:scale-110 {{ $isSaved ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>

                                    <span id="fav-text-{{ $artwork->id }}" class="text-lg font-medium">
                                        {{ $isSaved ? 'Disimpan' : 'Simpan' }}
                                    </span>
                                </button>
                            </div>

                            <form action="{{ route('report.store', $artwork->id) }}" method="POST" x-data="{ open: false }">
                                @csrf
                                <a href="#" @click.prevent="open = !open" class="text-xs text-gray-500 hover:text-red-600 hover:underline">
                                    <i class="fas fa-flag"></i> Laporkan karya ini
                                </a>
                                <div x-show="open" class="mt-2 border border-gray-200 p-2 rounded-md">
                                    <x-input-label for="reason" value="Alasan Laporan" />
                                    <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full" placeholder="Misal: Plagiat, SARA, dll" required />
                                    <x-primary-button class="mt-2">Kirim Laporan</x-primary-button>
                                </div>
                            </form>
                        @endauth

                        @guest
                            <div class="p-4 bg-gray-50 rounded-md text-sm text-gray-700">
                                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">Login</a> atau
                                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:underline">Register</a> untuk Like, Comment, dan Save.
                            </div>
                        @endguest

                    </div>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    <h3 class="text-2xl font-bold mb-6">Komentar</h3>

                    <div id="comments-list" class="space-y-6 mb-8">
                    @forelse($artwork->comments as $comment)
                        <div id="comment-{{ $comment->id }}" class="flex items-start space-x-4 group">

                            {{-- Avatar --}}
                            <img src="{{ Storage::url($comment->user->profile_picture) ?? asset('images/Default.png')  }}"
                                alt="User Avatar" class="w-10 h-10 rounded-full object-cover border flex-shrink-0">

                            <div class="flex-1">
                                <div class="bg-gray-100 p-4 rounded-lg rounded-tl-none relative">

                                    {{-- Header Komentar --}}
                                    <div class="flex justify-between items-start mb-1">
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm">{{ $comment->user->name }}</h4>
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>

                                        {{-- DROPDOWN MENU (Titik Tiga) --}}
                                        @auth
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                                </button>

                                                {{-- Isi Dropdown --}}
                                                <div x-show="open" @click.away="open = false"
                                                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20 border border-gray-200 py-1"
                                                    style="display: none;">

                                                    @if(Auth::id() === $comment->user_id)
                                                        {{-- Tombol Hapus (Milik Sendiri) --}}
                                                        <button onclick="deleteComment({{ $comment->id }})"
                                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                                            <i class="fas fa-trash-alt mr-2"></i> Hapus
                                                        </button>
                                                    @else
                                                        {{-- Tombol Lapor (Milik Orang Lain) --}}
                                                        <button onclick="openReportModal('comment', {{ $comment->id }})"
                                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                                            <i class="fas fa-flag mr-2"></i> Laporkan
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endauth
                                    </div>

                                    {{-- Isi Komentar --}}
                                    <p class="text-gray-800 text-sm whitespace-pre-wrap break-words">{{ $comment->body }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 italic text-center py-4" id="no-comments-text">Belum ada komentar. Jadilah yang pertama!</p>
                    @endforelse
                </div>

                {{-- MODAL REPORT (Universal untuk Artwork & Comment) --}}
                <div id="report-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 transform transition-all scale-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4" id="report-modal-title">Laporkan Konten</h3>

                        <form id="report-form" onsubmit="submitReport(event)">
                            @csrf
                            {{-- Input Hidden untuk menyimpan ID target --}}
                            <input type="hidden" id="report-type" name="type"> {{-- 'artwork' atau 'comment' --}}
                            <input type="hidden" id="report-target-id" name="target_id">

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pelaporan</label>
                                <textarea id="report-reason" name="reason" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Jelaskan alasan Anda (misal: Spam, SARA, Kasar)..." required></textarea>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeReportModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none">
                                    Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                    @auth
                        <form id="comment-form" onsubmit="submitComment(event, {{ $artwork->id }})" class="relative">
                            <div class="flex items-start space-x-4">
                                <img src="{{ Storage::url(auth()->user()->profile_picture) ?? asset('images/Default.png') }}" class="w-10 h-10 rounded-full object-cover border">
                                <div class="w-full">
                                    <textarea
                                        id="comment-body"
                                        rows="3"
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-3"
                                        placeholder="Tulis pendapatmu tentang karya ini..."
                                        required></textarea>

                                    <div class="mt-2 text-right">
                                        <button type="submit" class="bg-black text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-800 transition shadow-lg">
                                            Kirim Komentar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="bg-gray-50 p-4 rounded text-center">
                            <p>Silakan <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Login</a> untuk berkomentar.</p>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // --- FITUR LIKE ---
    function toggleLike(artworkId) {
        // Panggil route like di web.php
        axios.post('/like/artwork/' + artworkId  )
            .then(function (response) {
                const data = response.data;

                // Ambil elemen
                const countSpan = document.getElementById('like-count-' + artworkId);
                const btn = document.getElementById('like-btn-' + artworkId);

                // Update Angka
                countSpan.innerText = data.likes_count;

                // Update Warna (Visual Feedback)
                if (data.is_liked) {
                    btn.classList.remove('text-gray-500');
                    btn.classList.add('text-red-500');
                } else {
                    btn.classList.remove('text-red-500');
                    btn.classList.add('text-gray-500');
                }
            })
            .catch(function (error) {
                console.error(error);
                if(error.response.status === 401) {
                    alert('Silakan login terlebih dahulu!'); // Handle jika belum login
                }
            });
    }

    // --- FITUR KOMENTAR ---
    function submitComment(event, artworkId) {
        event.preventDefault(); // Mencegah reload form standar

        const bodyInput = document.getElementById('comment-body');
        const bodyValue = bodyInput.value;

        if (!bodyValue.trim()) return; // Jangan kirim jika kosong

        axios.post('/comment/artwork/' + artworkId, {
            body: bodyValue
        })
        .then(function (response) {
            const data = response.data;
            const newComment = data.data;

            // Buat HTML element baru untuk komentar (Append manual)
            // Anda bisa menyesuaikan HTML ini dengan desain komentar Anda
            const commentHtml = `
    <div id="comment-${newComment.id}" class="flex items-start space-x-4 animate-fade-in-down group">
        <img src="${data.user_avatar}" class="w-10 h-10 object-cover rounded-full border border-gray-200">

        <div class="flex-1">
            <div class="bg-gray-100 p-4 rounded-lg rounded-tl-none w-full relative">
                <div class="flex justify-between items-center mb-1">
                    <h4 class="font-bold text-gray-900 text-sm">${data.user_name || 'Anda'}</h4>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500">Baru saja</span>

                        {{-- Tombol Dropdown (Hanya visual di JS, fungsi belum aktif karena perlu bind event listener ulang) --}}
                        {{-- Untuk JS murni, tombol hapus langsung lebih mudah --}}
                        <button onclick="deleteComment(${newComment.id})" class="text-red-400 hover:text-red-600 text-xs ml-2" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <p class="text-gray-800 text-sm whitespace-pre-wrap break-words">${newComment.body}</p>
            </div>
        </div>
    </div>
`;

            // Masukkan ke dalam list komentar
            document.getElementById('comments-list').insertAdjacentHTML('beforeend', commentHtml);

            // Kosongkan input
            bodyInput.value = '';
        })
        .catch(function (error) {
            console.error(error);
            alert('Gagal mengirim komentar.');
        });
    }

    // --- FITUR FAVORITE / SAVE ---
function toggleFavorite(artworkId) {
    axios.post('/favorite/artwork/' + artworkId)
        .then(function (response) {
            const data = response.data;

            const btn = document.getElementById('fav-btn-' + artworkId);
            const icon = document.getElementById('fav-icon-' + artworkId);
            const text = document.getElementById('fav-text-' + artworkId);

            if (data.is_favorited) {
                // Style User Menyimpan (Kuning)
                btn.classList.remove('text-gray-500');
                btn.classList.add('text-yellow-500');
                icon.classList.remove('fill-none');
                icon.classList.add('fill-current');
                text.innerText = "Disimpan";
            } else {
                // Style User Batal Menyimpan (Abu-abu)
                btn.classList.remove('text-yellow-500');
                btn.classList.add('text-gray-500');
                icon.classList.remove('fill-current');
                icon.classList.add('fill-none');
                text.innerText = "Simpan";
            }
        })
        .catch(function (error) {
            if(error.response && error.response.status === 401) {
                alert('Silakan login untuk menyimpan karya ini.');
                window.location.href = '/login';
            }
        });
}

function deleteComment(commentId) {
    if(!confirm("Apakah Anda yakin ingin menghapus komentar ini?")) return;

    axios.delete('/comment/' + commentId)
        .then(function(response) {
            // Hapus elemen HTML komentar dari daftar
            const commentElement = document.getElementById('comment-' + commentId);
            if(commentElement) {
                commentElement.remove();

                // Cek jika komentar habis, tampilkan teks kosong
                const list = document.getElementById('comments-list');
                if(list.children.length === 0) {
                    list.innerHTML = '<p class="text-gray-500 italic text-center py-4" id="no-comments-text">Belum ada komentar. Jadilah yang pertama!</p>';
                }
            }
            alert('Komentar berhasil dihapus.');
        })
        .catch(function(error) {
            console.error(error);
            alert('Gagal menghapus komentar.');
        });
}

function openReportModal(type, id) {
    const modal = document.getElementById('report-modal');
    const title = document.getElementById('report-modal-title');
    const inputType = document.getElementById('report-type');
    const inputId = document.getElementById('report-target-id');
    const reason = document.getElementById('report-reason');

    // Set Data
    inputType.value = type; // 'artwork' atau 'comment'
    inputId.value = id;
    reason.value = ''; // Reset text area

    // Ubah Judul Modal
    if(type === 'artwork') {
        title.innerText = "Laporkan Karya Ini";
    } else {
        title.innerText = "Laporkan Komentar Ini";
    }

    // Tampilkan Modal
    modal.classList.remove('hidden');
}

function closeReportModal() {
    document.getElementById('report-modal').classList.add('hidden');
}

// --- SUBMIT REPORT ---
function submitReport(event) {
    event.preventDefault();

    const type = document.getElementById('report-type').value;
    const id = document.getElementById('report-target-id').value;
    const reason = document.getElementById('report-reason').value;

    // Siapkan Payload Data
    let data = { reason: reason };
    if (type === 'artwork') {
        data.artwork_id = id;
    } else {
        data.comment_id = id;
    }

    // Kirim ke Server
    // Pastikan route di web.php adalah: Route::post('/report', [ReportController::class, 'store']);
    // Jika route Anda '/report/artwork/{id}', Anda perlu route baru yang umum '/report/store'
    axios.post('/report/store', data)
        .then(function(response) {
            alert(response.data.message);
            closeReportModal();
        })
        .catch(function(error) {
            console.error(error);
            if(error.response && error.response.status === 401) {
                alert('Silakan login terlebih dahulu.');
            } else {
                alert('Gagal mengirim laporan. Silakan coba lagi.');
            }
        });
}
</script>
