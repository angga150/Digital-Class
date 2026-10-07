# Dashboard concept v1

Created with the built-in image generation tool on 2026-10-06.

Preview concept only; not yet implemented in Laravel. Desktop and mobile views share one presentation image: `dashboard-concept-v1.png`. All people and content are fictional example data.

Direction: modern scrapbook, ivory canvas, orange actions, sage announcement panel, butter-yellow birthday card, readable typography, restrained photo collage and doodles. Dashboard prioritizes class announcements, community stories, birthdays, agenda, and polls. Mobile uses a single column and labeled bottom navigation.

Implementation notes: preserve accessible contrast and touch targets; render text and UI as actual Blade/Livewire components rather than using this image as the page. Adapt layout to real viewport sizes; allow content to scroll. Check dates and visibility from real data, and use consented class photos. Replace inactive placeholders with real actions. Decorative motion must respect reduced-motion preferences.

## Generation prompt

Use case: ui-mockup
Create a polished high-fidelity UI design presentation image showing TWO coordinated dashboard mockups for "Digital Class", a private Indonesian university class community: a large desktop dashboard on the left (about 72% of composition) and a tall mobile dashboard on the right. Wide landscape canvas, high resolution, straight-on crisp flat UI, no perspective, no hands, no laptop hardware. Tiny understated captions DESKTOP and MOBILE outside the screens. All text in Indonesian and legible.

Art direction: modern editorial scrapbook, warm ivory background #FAF7F0, white cards, ink charcoal text, burnt orange #E97843 primary actions, pale butter yellow and sage green supporting panels. Elegant friendly rounded sans typography with bold expressive headings. Generous whitespace, precise consistent grid, restrained subtle borders and soft shadows, corners 18-24px. A few delightful handdrawn orange stars, washi tape accents and tiny stickers, carefully placed outside functional text. Sophisticated university community, warm and joyful, not childish, not corporate learning management system. Realistic candid small photographs of fictional Indonesian university friends, including diverse men and women, embedded in content cards. Photos never dominate information hierarchy. Accessible dark text, simple outlined icons with labels.

DESKTOP screen:
Slim left sidebar with orange abstract four-petal mark and "Digital Class", small class chip "Ruang kelas kita", navigation "Beranda" highlighted soft orange, "Cerita Kelas", "Agenda", "Kenangan", "Teman Kelas". At bottom "Pengaturan" and a small avatar with "Angga".
Top bar search field "Cari cerita, agenda, atau teman", bell icon, avatar.
Main heading small date "Selasa, 6 Oktober 2026", then "Hai, Angga!" and subheading "Ada cerita apa di kelas hari ini?" Primary orange button "Tulis cerita".
Below a clear slim important announcement banner with megaphone icon: "Info kelas" / "Ruang kuliah besok pindah ke B.204" and right arrow.
Below warm hero card with text "Momen kecil, kenangan panjang." and "Tempat cerita dan kenangan kelas kita." Small button "Lihat kenangan". To its right within the card a delightful modest collage of two taped candid campus photographs, orange star doodle. Keep this banner shallow, not a marketing landing page hero.
Then grid: broad left column "Cerita terbaru", short composer "Mau berbagi apa hari ini?" with small photo action; one beautiful feed card avatar "Nadia" time "20 menit lalu", text "Satu tugas selesai, satu kenangan baru!", candid group photo on campus, actions heart "12" speech bubble "3" and subtle ellipsis.
Narrower right column stacked:
butter yellow birthday card "Yang sebentar lagi ulang tahun", avatar "Rani", badge "2 hari lagi", tiny cake illustration and quiet CTA "Lihat kalender";
white upcoming agenda card "Agenda terdekat", date block "09 OKT", title "Presentasi kelompok", supporting "Jumat • 09.00", small green pill "3 hari lagi";
white polling card "Suara kelas" question "Mau kumpul di mana Jumat ini?" TWO visibly unselected radio options "Taman kampus" and "Kantin", orange button "Kirim pilihan", subtle "Berakhir besok". No percentages shown before voting.

MOBILE screen around 390px proportional width, with SAME palette, components, wording and hierarchy, genuinely adapted to one column, not miniaturized desktop. Top logo Digital Class, bell, avatar. Heading "Hai, Angga!" and subheading, compact info banner, small horizontal hero collage or compact memories banner, birthday card and agenda card stacked full width, beginning of Cerita terbaru composer if space. Fixed bottom navigation with labeled icons "Beranda", "Cerita", "Agenda", "Kenangan", active Beranda orange. Plenty of comfortable touch spacing. Screen can show a realistic first viewport and naturally crop continuing feed at bottom behind navigation, but never crop headings or overlap text.

Constraints: polished feasible Laravel/Tailwind app UI, credible readable concise Indonesian wording. No grades, no course completion progress, no analytics charts, no fake revenue, no leaderboard, no English generic dashboard labels. Balanced information density. This is a design concept using fictional example data, not a functioning app. Do not add external explanatory paragraphs or palette swatches.

