// Navigation and remaining example widgets. Persistent stories use Livewire.
document.addEventListener('alpine:init', () => {
    Alpine.data('classDashboard', () => ({
        active: 'beranda',
        modal: '',
        toast: '',
        toastTimer: null,
        get modalTitle() {
            return { announcement: 'Kabar untuk kelas', birthdays: 'Kalender ulang tahun', event: 'Agenda kelas', memory: 'Selesai tugas, lanjut cerita.', settings: 'Tentang dashboard ini' }[this.modal] || '';
        },
        openModal(name) {
            if (name === 'announcement') {
                window.location.href = document.body.dataset.announcementsUrl;
                return;
            }
            if (name === 'birthdays') {
                window.location.href = document.body.dataset.birthdaysUrl;
                return;
            }
            if (name === 'event') {
                window.location.href = document.body.dataset.eventsUrl;
                return;
            }
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
        scrollBehavior() { return matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth'; },
        destroy() { clearTimeout(this.toastTimer); },
    }));
});
