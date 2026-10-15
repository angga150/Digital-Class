<div>
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div><p class="text-xs font-bold uppercase tracking-[.18em] text-orange-dark">Kenalan lebih dekat</p><h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Ini ruang kecil tentangmu.</h1><p class="mt-3 text-sm text-muted">Nggak perlu formal. Cukup jadi dirimu sendiri.</p></div>
        <a href="{{ route('members.show', $member) }}" class="quiet-button">Lihat profilku <x-icon name="arrow" :size="16" /></a>
    </div>
    @if(! auth()->user()->hasVerifiedEmail())
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-[#efdfb3] bg-[#fff7df] p-4 text-sm">
        <p>✉️ Verifikasi email untuk mengunggah foto profil.</p><a href="{{ route('verification.notice') }}" class="quiet-button">Verifikasi email</a>
    </div>
    @endif
    <div class="grid items-start gap-7 lg:grid-cols-[280px_1fr]">
        <aside class="card overflow-hidden lg:sticky lg:top-6">
            <div class="paper-grid h-24 bg-sage"></div>
            <div class="px-6 pb-7">
                <div class="-mt-9 mb-4">
                @if($savedProfile?->avatar_path)
                    <img src="{{ route('members.avatar', $member) }}" alt="Foto profilmu" class="size-20 rounded-2xl border-4 border-white object-cover">
                @else
                    <span class="inline-flex size-20 items-center justify-center rounded-2xl border-4 border-white bg-[#f4dcc8] text-3xl font-bold text-[#834d2a]">{{ auth()->user()->initials }}</span>
                @endif
                </div>
                <h2 class="break-words text-xl font-bold">{{ auth()->user()->display_name }}</h2>
                <p class="mt-2 text-sm text-muted">{{ $member->classModel->name }}</p>
                <p class="mt-4 inline-flex rounded-full bg-sage px-3 py-1 text-xs font-semibold text-[#526443]">{{ $member->role === 'admin' ? 'Pengurus kelas' : 'Anggota kelas' }}</p>
                <div class="mt-6 border-t border-line pt-5"><p class="font-story text-sm">Sedikit cerita, lebih dekat ♡</p><p class="mt-2 text-xs leading-6 text-muted">Hobi yang sama bisa jadi awal obrolan. Isi yang nyaman kamu bagikan saja.</p></div>
                @if($member->role === 'admin')
                <details class="mt-5 border-t border-line pt-5"><summary class="cursor-pointer text-sm font-semibold text-orange-dark">Undang teman sekelas</summary><p class="mt-3 text-xs leading-6 text-muted">Bagikan kode ini hanya kepada anggota kelasmu.</p><code class="mt-2 block break-all rounded-xl bg-paper p-3 text-sm select-all">{{ $member->classModel->join_code }}</code></details>
                @endif
            </div>
        </aside>
        <form wire:submit="save" class="space-y-6">
            <section class="card p-5 sm:p-7">
                <h2 class="text-lg font-bold">01 <span class="ml-2">Halo, ini aku</span></h2>
                <div class="mt-6 space-y-5">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.input name="name" label="Nama lengkap" wire:model="name" required maxlength="100" autocomplete="name" />
                        <x-form.input name="nickname" label="Panggil aku…" wire:model="nickname" maxlength="50" placeholder="Nama panggilanmu" />
                    </div>
                    <div><label for="avatar" class="mb-2 block text-sm font-semibold">Foto profil <span class="font-normal text-muted">· opsional</span></label>
                        <input id="avatar" type="file" wire:model="avatar" accept="image/jpeg,image/png,image/webp" class="field file:mr-3 file:rounded-lg file:border-0 file:bg-sage file:px-3 file:py-2 file:text-sm" @disabled(! auth()->user()->hasVerifiedEmail()) aria-describedby="avatar-help">
                        <p id="avatar-help" class="mt-2 text-xs text-muted">JPG, PNG, atau WebP. Maksimal 5 MB. Tanpa foto, kami pakai inisial namamu.</p>
                        <p wire:loading wire:target="avatar" role="status" class="mt-2 text-xs text-orange-dark">Sedang mengunggah foto…</p>
                        @error('avatar')<p role="alert" class="mt-2 text-xs text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div><label for="bio" class="mb-2 block text-sm font-semibold">Sedikit tentangku</label><textarea id="bio" wire:model="bio" rows="3" maxlength="500" class="field" placeholder="Tim kopi sore, suka desain, dan selalu bawa camilan…"></textarea>@error('bio')<p class="text-xs text-red-700">{{ $message }}</p>@enderror</div>
                </div>
            </section>
            <section class="card p-5 sm:p-7">
                <h2 class="text-lg font-bold">02 <span class="ml-2">Bahan obrolan seru</span></h2>
                <div class="mt-6 space-y-5">
                    <x-form.input name="hobby" label="Kalau lagi senggang…" wire:model="hobby" maxlength="250" placeholder="Fotografi, main game, baca komik" />
                    <x-form.input name="favorite" label="Hal-hal favoritku" wire:model="favorite" maxlength="250" placeholder="Matcha, hujan, film animasi" />
                    <p class="text-xs text-muted">Pisahkan hobi dan favorit dengan koma.</p>
                    <div><label for="fun_fact" class="mb-2 block text-sm font-semibold">Satu fakta random tentangku</label><textarea id="fun_fact" wire:model="fun_fact" rows="2" maxlength="500" class="field" placeholder="Pernah…"></textarea>@error('fun_fact')<p class="text-xs text-red-700">{{ $message }}</p>@enderror</div>
                </div>
            </section>
            <section class="card p-5 sm:p-7">
                <h2 class="text-lg font-bold">03 <span class="ml-2">Nyaman berbagi</span></h2>
                <p class="mt-2 text-sm leading-6 text-muted">Kamu yang memilih siapa yang boleh melihat ceritamu. Nama lengkap dan keanggotaan tetap terlihat oleh teman sekelas.</p>
                <div class="mt-6 space-y-5">
                    <x-form.input name="birth_date" label="Tanggal lahir · opsional" type="date" wire:model="birth_date" min="1900-01-01" :max="now()->format('Y-m-d')" />
                    <label class="flex min-h-11 items-center gap-3 text-sm"><input type="checkbox" wire:model="birth_year_visible" class="size-4 accent-orange-dark">Tampilkan juga tahun lahir</label>
                    @foreach(['profile_visibility' => 'Siapa yang bisa melihat foto, bio, dan cerita profil?', 'birthday_visibility' => 'Siapa yang bisa melihat ulang tahun?'] as $field => $label)
                    <div><label for="{{ $field }}" class="mb-2 block text-sm font-semibold">{{ $label }}</label><select id="{{ $field }}" wire:model="{{ $field }}" class="field"><option value="class_only">Teman satu kelas</option><option value="admin_only">Pengurus kelas saja</option><option value="private">Hanya aku</option></select>@error($field)<p class="text-xs text-red-700">{{ $message }}</p>@enderror</div>
                    @endforeach
                    <p class="rounded-xl bg-sage p-3 text-xs leading-6 text-[#526443]">Jika profil dipilih “Hanya aku”, seluruh detail termasuk ulang tahun ikut disembunyikan dari anggota lain. Kalender dan widget juga mengikuti pilihan privasi ini; tanggal privat tidak memunculkan pengingat untuk teman sekelas.</p>
                </div>
            </section>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs text-muted">Cerita bisa diubah kapan saja.</p>
                <button class="primary-button" type="submit" wire:loading.attr="disabled" wire:target="save,avatar"><x-icon name="check" :size="18" /><span wire:loading.remove wire:target="save">Simpan profilku</span><span wire:loading wire:target="save">Menyimpan…</span></button>
            </div>
        </form>
    </div>
</div>
