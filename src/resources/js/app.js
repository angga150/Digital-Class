// Deliberately transient demo state. Production mutations must use Livewire
// validation, authentication, class scopes, and Policies before persistence.
document.addEventListener('alpine:init', () => {
    Alpine.data('classDashboard', () => ({
        active: 'beranda',
        modal: '',
        toast: '',
        toastTimer: null,
        search: '',
        searchOpen: false,
        notificationsRead: false,
        draft: '',
        draftError: '',
        commentDraft: '',
        commentError: '',
        selectedPost: 1,
        pollChoice: '',
        voted: false,
        posts: [
            { id: 1, author: 'Nadia', initials: 'N', tone: 'bg-[#ede4f3] text-[#76518d]', time: '20 menit lalu', body: 'Satu tugas selesai, satu kenangan baru! 🌿\nTim paling susah diajak serius, tapi selalu bisa diandalkan.', photo: true, likes: 12, liked: false, saved: false,
                comments: [{ id: 1, author: 'Rani', text: 'Habis ini kita rayakan di kantin ya!' }, { id: 2, author: 'Bima', text: 'Presentasi aman, perut harus aman juga 😄' }] },
            { id: 2, author: 'Bima', initials: 'B', tone: 'bg-[#e6ead9] text-[#526443]', time: '1 jam lalu', body: 'Ada yang mau latihan presentasi bareng besok? Ketemu di perpustakaan setelah kelas, yuk. ☕', photo: false, likes: 5, liked: false, saved: false, comments: [] },
        ],
        get modalTitle() {
            return { compose: 'Tulis cerita', comments: 'Obrolan kecil', announcement: 'Kabar untuk kelas', birthdays: 'Kalender ulang tahun', event: 'Agenda kelas', memory: 'Selesai tugas, lanjut cerita.', members: 'Teman kelas kita', profile: 'Hai, ini Angga!', notifications: 'Pemberitahuan', settings: 'Tentang dashboard ini' }[this.modal] || '';
        },
        get currentPost() { return this.posts.find(post => post.id === this.selectedPost); },
        get searchResults() {
            const entries = [
                { label: 'Cerita Nadia · satu tugas selesai', target: 'cerita' },
                { label: 'Cerita Bima · latihan presentasi', target: 'cerita' },
                { label: 'Agenda · presentasi kelompok', modal: 'event' },
                { label: 'Rani · ulang tahun', modal: 'birthdays' },
                { label: 'Teman kelas · cari anggota kelasmu', modal: 'members' },
                { label: 'Kenangan · album kehidupan kampus', target: 'kenangan' },
                { label: 'Info kelas · ruang B.204', modal: 'announcement' },
            ];
            const query = this.search.trim().toLocaleLowerCase('id');
            return query ? entries.filter(entry => entry.label.toLocaleLowerCase('id').includes(query)) : [];
        },
        get pollResults() {
            const gardenVotes = 8 + (this.pollChoice === 'taman' ? 1 : 0);
            const percent = Math.round(gardenVotes / 13 * 100);
            return [{ label: 'Taman kampus', percent }, { label: 'Kantin', percent: 100 - percent }];
        },
        openModal(name) {
            if (name === 'members' || name === 'profile') {
                window.location.href = document.body.dataset[name + 'Url'];
                return;
            }
            this.modal = name;
            this.draftError = '';
            this.commentError = '';
            if (name === 'notifications') this.notificationsRead = true;
            if (name === 'comments') this.commentDraft = '';
            this.searchOpen = false;
            // Search results disappear before nextTick; resolve stable DOM targets
            // here instead of relying on the removed result's Alpine $refs scope.
            const dialog = document.getElementById('dashboard-dialog');
            const focusId = name === 'compose' ? 'story-draft' : name === 'comments' ? 'comment-draft' : 'dialog-title';
            this.$nextTick(() => {
                if (!dialog.open) dialog.showModal();
                document.getElementById(focusId).focus();
            });
        },
        closeModal() { document.getElementById('dashboard-dialog').close(); this.modal = ''; },
        notify(message) {
            clearTimeout(this.toastTimer);
            this.toast = message;
            this.toastTimer = setTimeout(() => this.toast = '', 4500);
        },
        publish() {
            const body = this.draft.trim();
            if (!body || body.length > 500) {
                this.draftError = 'Tulis cerita antara 1–500 karakter dulu, ya.';
                return;
            }
            this.posts.unshift({ id: Date.now(), author: document.body.dataset.userName, initials: document.body.dataset.userInitials, tone: 'bg-[#f4dcc8] text-[#834d2a]', time: 'Baru saja', body, photo: false, likes: 0, liked: false, saved: false, comments: [] });
            this.draft = '';
            this.closeModal();
            this.active = 'cerita';
            document.getElementById('cerita').scrollIntoView({ behavior: this.scrollBehavior() });
            this.notify('Cerita ditambahkan ke demo. Hilang saat halaman dimuat ulang.');
        },
        addComment() {
            const text = this.commentDraft.trim();
            if (!text || text.length > 300) {
                this.commentError = 'Isi komentar antara 1–300 karakter.';
                return;
            }
            if (!this.currentPost) return;
            this.currentPost.comments.push({ id: Date.now(), author: document.body.dataset.userName, text });
            this.commentDraft = '';
            this.commentError = '';
            this.notify('Komentar ditambahkan ke demo.');
        },
        vote() {
            if (this.voted || !['taman', 'kantin'].includes(this.pollChoice)) return;
            this.voted = true;
            this.notify('Terima kasih! Ini simulasi polling, bukan voting sungguhan.');
        },
        scrollBehavior() { return matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth'; },
        goToResult(result) {
            this.searchOpen = false;
            this.search = '';
            if (result.modal) this.openModal(result.modal);
            else {
                this.active = result.target;
                document.getElementById(result.target)?.scrollIntoView({ behavior: this.scrollBehavior() });
            }
        },
        destroy() { clearTimeout(this.toastTimer); },
    }));
});
