// --------------------------------------------------------------------------
// JAVASCRIPT: Interactivity for 17-an Infographic
// Features: Tab switching, interactive Pos guide, RT Log calculator, localStorage checklist
// --------------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {

  // ==========================================
  // 1. TAB NAVIGATION
  // ==========================================
  const tabButtons = document.querySelectorAll('.tab-btn');
  const tabContents = document.querySelectorAll('.tab-content');

  tabButtons.forEach(button => {
    button.addEventListener('click', () => {
      const targetTab = button.getAttribute('data-tab');

      // Update button state
      tabButtons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');

      // Update content state
      tabContents.forEach(content => {
        content.classList.remove('active');
        if (content.getAttribute('id') === targetTab) {
          content.classList.add('active');
        }
      });
    });
  });

  // ==========================================
  // 2. INTERACTIVE POS DISPLAY (OUTBOUND)
  // ==========================================
  const posData = {
    "1": {
      title: "Pos 1: Jejak Pejuang",
      location: "Area sawah / jalan setapak dusun",
      games: "Estafet bakiak kelompok sambil membawa bendera merah putih kecil secara bergantian. Di ujung lintasan, tim harus menjawab 1 pertanyaan sejarah kemerdekaan Indonesia.",
      assess: "Kecepatan waktu menyelesaikan estafet (maks 50 poin) + Ketepatan jawaban sejarah (50 poin). Total maks 100 poin.",
      equipment: [
        "2 set bakiak kelompok (isi 3 orang per bakiak)",
        "Bendera merah putih kecil dengan gagang kayu",
        "Kartu amplop soal sejarah perjuangan"
      ]
    },
    "2": {
      title: "Pos 2: Apotik Hidup",
      location: "Pekarangan herbal / kebun warga",
      games: "Tebak 10 jenis tanaman herbal/empon-empon segar (jahe, kencur, kunyit, daun sirih, dll) dengan mata ditutup kain (menggunakan indera penciuman) & meraba bentuk fisik daun/rimpang.",
      assess: "Setiap tebakan yang benar bernilai 10 poin. Total maksimal 100 poin.",
      equipment: [
        "10 jenis tanaman herbal segar di dalam wadah tampah",
        "Kain penutup mata hitam (3 buah)",
        "Lembar panduan juri untuk kunci jawaban"
      ]
    },
    "3": {
      title: "Pos 3: Sandi Alam",
      location: "Kebun bambu / area rimbun pohon",
      games: "Memecahkan teka-teki sandi rahasia yang ditulis di media alami (seperti daun talas lebar menggunakan lidi, atau ranting kayu). Sandi mengarahkan ke koordinat 'harta karun' tersembunyi.",
      assess: "Kecepatan memecahkan sandi (maks 80 poin) + Poin bonus penemuan harta karun (20 poin). Total maks 100 poin.",
      equipment: [
        "Daun talas lebar & lidi kelapa sebagai media tulis",
        "Spidol hitam tahan air (waterproof) untuk panitia",
        "Bungkusan 'Harta Karun' berisi koin kayu bonus"
      ]
    },
    "4": {
      title: "Pos 4: Gotong Royong",
      location: "Halaman rumah warga yang luas",
      games: "Estafet air menggunakan bilah bambu yang dibelah dua. Seluruh anggota tim harus berbaris menyambung bambu tanpa bocor agar air mengalir dari gelas start ke ember ukur di finish.",
      assess: "Volume air yang terkumpul dalam waktu 5 menit (skala mililiter dikonversi ke skor, maks 100 poin).",
      equipment: [
        "6 bilah bambu belah (panjang masing-masing 1.5 meter)",
        "Ember sumber air di titik start",
        "Ember transparan dengan garis ukur volume di titik finish",
        "Gelas plastik penakar air"
      ]
    },
    "5": {
      title: "Pos 5: Hafalan Berkah",
      location: "Pendopo dusun / teras masjid luar",
      games: "Cerdas cermat keagamaan & hafalan surat pendek (Juz 30) dikombinasikan dengan sejarah desa. Sistem rebutan menggunakan bel bambu tradisional (thethekan).",
      assess: "Jawaban benar +10 poin, jawaban salah -5 poin. Total akumulasi nilai selama 15 menit sesi tanya jawab.",
      equipment: [
        "Bel bambu tradisional (thethekan) untuk tiap regu",
        "Daftar kartu soal kategori tajwid, hafalan surat, & sejarah desa",
        "Papan tulis kapur kecil untuk rekap nilai sementara"
      ]
    },
    "6": {
      title: "Pos 6: Membangun Desa (Final)",
      location: "Lapangan Utama Desa (Titik Finish)",
      games: "Tantangan kreativitas tim untuk merakit maket/miniatur gapura kemerdekaan ramah lingkungan menggunakan limbah alam sisa (ranting pohon, daun kelapa/janur, sabut kelapa, tanah liat, dll).",
      assess: "Dinilai oleh 3 Tokoh Desa berdasarkan: Kekompakan tim (30%), Kreativitas desain (40%), dan Kekokohan struktur maket (30%).",
      equipment: [
        "Papan alas tripleks bekas ukuran 30x40 cm",
        "Gunting, cutter, dan tali serat bambu pengikat",
        "Aneka material organik (disediakan di area kumpul panitia)"
      ]
    }
  };

  const posButtons = document.querySelectorAll('.pos-btn');
  const posDetailContainer = document.getElementById('pos-detail-container');

  function renderPosDetail(posId) {
    const data = posData[posId];
    if (!data) return;

    // Create equipment list items
    const equipmentItems = data.equipment.map(item => `<li>${item}</li>`).join('');

    posDetailContainer.innerHTML = `
      <div class="pos-detail-header">
        <h3>${data.title}</h3>
        <span class="pos-location-badge">📍 Lokasi: ${data.location}</span>
      </div>
      
      <div class="pos-grid-detail">
        <div class="pos-sub-block">
          <h4>
            <span class="sub-icon">🎮</span>
            Konsep & Cara Main
          </h4>
          <p>${data.games}</p>
        </div>
        
        <div class="pos-sub-block">
          <h4>
            <span class="sub-icon">🔧</span>
            Kebutuhan Peralatan
          </h4>
          <ul>
            ${equipmentItems}
          </ul>
        </div>
      </div>
      
      <div class="pos-scoring-box">
        <h4>📋 Metode Penilaian Juri:</h4>
        <p>${data.assess}</p>
      </div>
    `;
  }

  // Handle Pos button clicks
  posButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      posButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const posId = btn.getAttribute('data-pos');
      renderPosDetail(posId);
    });
  });

  // Initial render (Pos 1)
  renderPosDetail("1");

  // ==========================================
  // 3. ECO LENDING LOGISTICS CALCULATOR
  // ==========================================
  const rtSelect = document.getElementById('rt-select');
  const calcTampah = document.getElementById('calc-tampah');
  const calcKendi = document.getElementById('calc-kendi');
  const calcGelas = document.getElementById('calc-gelas');
  const calcTikar = document.getElementById('calc-tikar');

  const rtLogisticsData = {
    "rt01": { tampah: 4, kendi: 2, gelas: 35, tikar: 4 },
    "rt02": { tampah: 5, kendi: 3, gelas: 40, tikar: 5 },
    "rt03": { tampah: 3, kendi: 2, gelas: 30, tikar: 3 },
    "rt04": { tampah: 4, kendi: 2, gelas: 25, tikar: 4 }
  };

  if (rtSelect) {
    rtSelect.addEventListener('change', (e) => {
      const selectedRt = e.target.value;
      const data = rtLogisticsData[selectedRt];
      
      if (data) {
        // Apply smooth transition values
        calcTampah.textContent = `${data.tampah} Unit`;
        calcKendi.textContent = `${data.kendi} Unit`;
        calcGelas.textContent = `${data.gelas} Unit`;
        calcTikar.textContent = `${data.tikar} Lembar`;
      }
    });
  }

  // ==========================================
  // 4. TIMELINE CHECKLIST & PROGRESS
  // ==========================================
  const checkboxes = document.querySelectorAll('.timeline-checkbox');
  const progressFill = document.getElementById('timeline-progress-fill');

  // Load saved checklist states
  checkboxes.forEach(checkbox => {
    const savedState = localStorage.getItem(checkbox.id);
    if (savedState === 'true') {
      checkbox.checked = true;
      // Add completed styling class to the card
      checkbox.closest('.timeline-step-card').classList.add('completed');
    }

    checkbox.addEventListener('change', () => {
      // Save state
      localStorage.setItem(checkbox.id, checkbox.checked);
      
      // Toggle class
      const card = checkbox.closest('.timeline-step-card');
      if (checkbox.checked) {
        card.classList.add('completed');
      } else {
        card.classList.remove('completed');
      }

      // Recalculate progress
      updateProgress();
    });
  });

  function updateProgress() {
    if (checkboxes.length === 0) return;
    const checkedCount = document.querySelectorAll('.timeline-checkbox:checked').length;
    const percentage = Math.round((checkedCount / checkboxes.length) * 100);
    
    progressFill.style.width = `${percentage}%`;
    progressFill.textContent = `${percentage}% Kesiapan`;
  }

  // Initial progress update on load
  updateProgress();

  // ==========================================
  // 5. ACCORDION (PLAN B / RISK MITIGATION)
  // ==========================================
  const accordionHeaders = document.querySelectorAll('.accordion-header');

  accordionHeaders.forEach(header => {
    header.addEventListener('click', () => {
      const item = header.parentElement;
      const isActive = item.classList.contains('active');

      // Close all accordions first (for a clean single-open behavior)
      document.querySelectorAll('.accordion-item').forEach(i => i.classList.remove('active'));

      // If it wasn't active, open it
      if (!isActive) {
        item.classList.add('active');
      }
    });
  });

});
