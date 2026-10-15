// Navigation and remaining example widgets. Persistent stories use Livewire.
document.addEventListener('alpine:init', () => {
    Alpine.data('classDashboard', () => ({
        active: 'beranda',
        modal: '',
        toast: '',
        toastTimer: null,
        search: '',
        searchOpen: false,
        notificationsRead: false,
        pollChoice: '',
        voted: false,
        get modalTitle() {
            return { announcement: 'Kabar untuk kelas', birthdays: 'Kalender ulang tahun', event: 'Agenda kelas', memory: 'Selesai tugas, lanjut cerita.', notifications: 'Pemberitahuan', settings: 'Tentang dashboard ini' }[this.modal] || '';
        },
        get searchResults() {
            const entries = [
                { label: 'Cerita kelas · kabar terbaru dari teman', target: 'cerita' },
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
            if (name === 'compose') {
                document.getElementById('story-compose')?.scrollIntoView({ behavior: this.scrollBehavior() });
                document.getElementById('story-body')?.focus({ preventScroll: true });
                return;
            }
            if (name === 'members' || name === 'profile') {
                window.location.href = document.body.dataset[name + 'Url'];
                return;
            }
            this.modal = name;
            if (name === 'notifications') this.notificationsRead = true;
            this.searchOpen = false;
            // Search results disappear before nextTick; resolve stable DOM targets
            // here instead of relying on the removed result's Alpine $refs scope.
            const dialog = document.getElementById('dashboard-dialog');
            const focusId = 'dialog-title';
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
