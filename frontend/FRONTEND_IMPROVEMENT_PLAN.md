# Frontend Improvement Plan

Dokumen ini adalah backlog implementasi frontend portfolio. Prioritasnya
berdasarkan dampak terhadap recruiter dan calon klien, bukan sekadar perubahan
dekoratif.

## Aturan konten

- Gunakan Bahasa Inggris sebagai bahasa utama untuk recruiter internasional;
  pastikan setiap perubahan juga memiliki terjemahan Bahasa Indonesia.
- Utamakan bukti kerja: angka, hasil, screenshot produk, dan tautan demo.
- Jangan menambahkan klaim yang belum dapat dibuktikan.
- Semua tautan eksternal harus memakai `target="_blank"` dan `rel="noreferrer"`.

## Status saat ini

- [x] Dark/light theme dan pilihan bahasa.
- [x] Satu request bootstrap untuk seluruh konten portfolio.
- [x] Kartu proyek memiliki fallback gambar sementara.
- [ ] Screenshot asli proyek.
- [ ] Featured case study untuk Aceh Cinema.
- [ ] Hero dengan positioning dan bukti yang lebih spesifik.
- [ ] Ringkasan pengalaman yang mudah dipindai.
- [ ] Metadata untuk social sharing dan SEO.

## P0 — Ganti gambar sementara dengan screenshot asli

File utama: `src/components/Projects.tsx`

Saat project belum memiliki `image_url` dari API, kartu sekarang menampilkan
gambar placeholder stabil dari `picsum.photos` berdasarkan judul project.
Gambar ini hanya untuk tahap pengembangan visual. Jika field `image` di CMS
diisi, URL gambar dari backend diprioritaskan secara otomatis.

Langkah penggantian:

1. Siapkan screenshot 16:9 dengan ukuran minimal 1280 × 720 px.
2. Hindari data sensitif, token, email pengguna, atau data transaksi asli.
3. Buka `/admin` pada backend, lalu pilih **Projects**.
4. Edit project dan unggah gambar melalui field **Image**.
5. Pastikan crop gambar tetap jelas pada rasio 16:9.
6. Periksa bahasa EN dan ID di public site setelah menyimpan.

Kriteria selesai:

- Aceh Cinema memiliki screenshot nyata.
- Semua proyek yang boleh dipublikasikan memiliki gambar sendiri.
- Tidak ada placeholder yang tersisa sebelum portfolio dibagikan luas.

## P1 — Featured case study: Aceh Cinema

Tujuan: menjadikan pengalaman paling kuat sebagai alasan utama pengunjung
menghubungi Anda.

Implementasi:

1. Buat `src/components/FeaturedProject.tsx`.
2. Ambil project dengan `featured === true` di `Projects.tsx`.
3. Tampilkan featured project dalam layout dua kolom: screenshot di satu sisi,
   ringkasan peran, stack, dan CTA di sisi lain.
4. Tambahkan tiga bagian ringkas: **Challenge**, **What I built**, dan
   **Result / scope**.
5. Gunakan grid kartu saat ini hanya untuk proyek non-featured.

Konten yang direkomendasikan untuk Aceh Cinema:

- 40+ Laravel REST API endpoints.
- Midtrans Snap + webhook payment flow.
- Rental access otomatis selama 24 jam.
- Google OAuth, Sanctum, dan arsitektur multi-subdomain.
- Portal creator dengan laporan yang dibatasi per pemilik konten.

Kriteria selesai:

- Featured project terlihat sebelum kartu proyek lain.
- CTA demo tidak bersaing dengan CTA lain.
- Informasi dapat dipindai dalam kurang dari 30 detik.

## P1 — Perjelas hero

File utama: `src/components/Hero.tsx`

Ubah pesan dari jabatan umum menjadi spesialisasi yang dapat dibuktikan.

Contoh arah copy (EN):

> Laravel Backend Developer building payment-integrated rental and content platforms.

Tambahkan baris bukti di bawah tagline, misalnya:

- `40+ REST API endpoints`
- `Midtrans · OAuth · Dacast`
- `Laravel · React · MySQL`

Urutan CTA:

1. Primary: View featured project.
2. Secondary: Download CV.
3. Tertiary: GitHub dan LinkedIn sebagai link sekunder.

Kriteria selesai:

- Pengunjung langsung mengetahui spesialisasi Anda dari hero.
- Hanya ada satu CTA utama.

## P2 — Ringkas experience dan skills

File utama: `src/components/Experience.tsx` dan `src/components/Skills.tsx`

Experience:

1. Tampilkan tiga bullet paling berdampak terlebih dahulu.
2. Tambahkan tombol **Show more / Show less** bila bullet lebih dari tiga.
3. Tetap tampilkan stack teknologi secara ringkas.

Skills:

1. Sorot skill inti: Laravel, React, MySQL, REST API, Midtrans, OAuth.
2. Ubah nama `AI-Assisted Development` menjadi `Engineering Workflow`, atau
   pindahkan ke kelompok Tools.
3. Jangan memakai indikator level kecuali dapat dijelaskan secara konsisten.

Kriteria selesai:

- Bagian pengalaman tidak terasa sebagai CV panjang.
- Skill utama terlihat tanpa perlu membaca seluruh daftar.

## P2 — UX, aksesibilitas, dan mobile

File utama: `src/components/Navbar.tsx` dan `src/index.css`

- Tambahkan `aria-expanded` pada tombol menu mobile.
- Tutup menu saat tombol Escape ditekan.
- Tandai nav link dari section yang sedang aktif.
- Tambahkan focus style `:focus-visible` untuk link dan tombol.
- Tambahkan tombol back-to-top setelah melewati hero.
- Uji pada lebar 360 px, 768 px, dan desktop.

## P3 — SEO dan social preview

File utama: `index.html` dan `src/main.tsx`

- Tambahkan canonical URL.
- Tambahkan Open Graph dan Twitter card metadata.
- Buat gambar social preview 1200 × 630 px.
- Tambahkan JSON-LD tipe `Person` berisi nama, role, GitHub, LinkedIn, dan
  lokasi umum.
- Sinkronkan atribut `lang` HTML dengan bahasa yang sedang dipilih.

## Verifikasi sebelum deploy

Jalankan dari folder `frontend`:

```powershell
npm run lint
npm run build
```

Periksa secara manual:

- Semua gambar dimuat, termasuk fallback saat project belum memiliki image.
- Gambar asli dari CMS menggantikan fallback.
- Tidak ada horizontal scroll pada mobile.
- Tema terang/gelap dan EN/ID tetap berfungsi.
- Semua CTA mengarah ke tujuan yang tepat.

## Catatan backend

Tidak diperlukan perubahan backend untuk placeholder ini. Model `Project`,
API resource, dan form Filament sudah memiliki field upload `image`; frontend
secara otomatis memakai `image_url` jika tersedia.
