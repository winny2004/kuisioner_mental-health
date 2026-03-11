# Sistem Kuisioner Mental Health

## 📊 Overview

Sistem ini memiliki 2 jenis kuisioner dengan pendekatan yang berbeda:

### 1. Family Social Factor (Dengan Machine Learning)
- **Total Pertanyaan:** 33 (12 Family Social + 21 DASS-21)
- **ML Integration:** ✅ Ya
- **Output:** Klasifikasi (Normal/Depression/Anxiety/Stress) dengan probabilitas
- **DASS-21 Breakdown:** Depresi, Kecemasan, Stress dengan label severity

### 2. Self Efficacy (Tanpa Machine Learning)
- **Total Pertanyaan:** 28 (10 Self-Efficacy + 18 Well-being)
- **ML Integration:** ❌ Tidak
- **Output:** Kategori berdasarkan persentase (Tinggi/Sedang/Rendah)
- **Sistem:** Perhitungan skor biasa

---

## 🤖 Machine Learning Implementation

### Hanya untuk Family Social Factor

Machine Learning **HANYA** diterapkan pada kuisioner **Family Social Factor** dengan alasan:

1. **DASS-21 adalah standar internasional** untuk mengukur depresi, kecemasan, dan stress
2. **Mempunyai dataset yang cukup** untuk training model ML
3. **Self Efficacy menggunakan skala berbeda** yang tidak cocok dengan model DASS-21

### Cara Kerja ML

```
User Isi Family Social → Hitung Skor DASS-21 → Kirim ke ML API → Klasifikasi
                                                          ↓
                                            Normal/Depression/Anxiety/Stress
```

### Fallback Mechanism

Jika ML API tidak tersedia:
- Sistem otomatis menggunakan perhitungan persentase
- User tetap mendapatkan hasil kuisioner
- Tidak ada error atau crash

---

## 📝 Detail Kuisioner

### Family Social Factor

#### Section 1: Family Social (12 pertanyaan)
- **Skala:** Likert 1-5 (Sangat Tidak Setuju - Sangat Setuju)
- **Topik:** Dukungan keluarga dan teman

#### Section 2: DASS-21 (21 pertanyaan)
- **Skala:** 0-3 (Tidak berlaku - Berlaku sangat banyak)
- **Sub-skala:**
  - Stress: 7 pertanyaan
  - Anxiety: 7 pertanyaan
  - Depression: 7 pertanyaan

#### Output ML
- **Prediksi:** Normal, Depression, Anxiety, atau Stress
- **Akurasi:** Persentase confidence
- **Probabilitas:** Distribusi probabilitas setiap kategori
- **Severity:** Normal, Mild, Moderate, Severe, Extremely Severe

### Self Efficacy

#### Section 1: Self-Efficacy (10 pertanyaan)
- **Skala:** Likert 1-4 (Sangat Tidak Setuju - Sangat Setuju)
- **Topik:** Kepercayaan diri dan kemampuan mengatasi masalah

#### Section 2: Well-being (18 pertanyaan)
- **Skala:** Likert 1-7 (Sangat Setuju - Sangat Tidak Setuju)
- **Topik:** Kesejahteraan psikologis

#### Output
- **Kategori:** Tinggi (≥75%), Sedang (≥50%), Rendah (<50%)
- **Feedback:** Berdasarkan kategori pencapaian

---

## 🔧 Technical Implementation

### Code Logic (QuizController.php)

```php
// Hanya family_social yang menggunakan ML
if ($type === 'family_social') {
    // Hitung skor DASS-21
    // Hitung skor MSPSS (family social support)
    // Kirim ke ML API
    $mlPrediction = $this->getMLPrediction(...);
}

// Gunakan ML prediction hanya untuk family_social
if ($type === 'family_social' && $mlPrediction && $mlPrediction['success']) {
    $category = $mlPrediction['prediction']; // Normal/Depression/Anxiety/Stress
    $feedback = $this->getMLFeedback($mlPrediction);
} else {
    // Fallback untuk self_efficacy atau jika ML gagal
    $percentage = ($totalScore / $maxScore) * 100;
    // Kategori: tinggi/sedang/rendah
}
```

### View Logic (result.blade.php)

```blade
@if($type === 'family_social' && $result->ml_prediction)
    {{-- Tampilkan hasil ML --}}
    🤖 Prediksi AI: Normal/Depression/Anxiety/Stress
    📊 Probabilitas setiap kategori
    📈 Breakdown DASS-21 (Depresi, Kecemasan, Stress)
@else
    {{-- Tampilkan hasil biasa (untuk Self Efficacy) --}}
    📊 Skor Total
    🏷️ Kategori: Tinggi/Sedang/Rendah
@endif
```

---

## 🚀 Cara Menggunakan

### Untuk Family Social Factor (Dengan ML)

1. **Opsional: Jalankan ML API**
   ```bash
   # Buka terminal baru
   cd path/to/ml/project
   python ml_api.py
   # ML API berjalan di http://localhost:5000
   ```

2. **Isi Kuisioner**
   - Login ke sistem
   - Pilih "Family Social Factor"
   - Jawab 33 pertanyaan

3. **Lihat Hasil**
   - Jika ML API aktif: Hasil prediksi AI
   - Jika ML API tidak aktif: Hasil berdasarkan persentase

### Untuk Self Efficacy (Tanpa ML)

1. **Isi Kuisioner**
   - Login ke sistem
   - Pilih "Self Efficacy"
   - Jawab 28 pertanyaan

2. **Lihat Hasil**
   - Skor total
   - Kategori: Tinggi/Sedang/Rendah
   - Feedback berdasarkan kategori

---

## 📊 Perbandingan Output

| Fitur | Family Social Factor | Self Efficacy |
|-------|---------------------|---------------|
| **Total Pertanyaan** | 33 | 28 |
| **ML Integration** | ✅ Ya | ❌ Tidak |
| **Kategori Output** | Normal/Depression/Anxiety/Stress | Tinggi/Sedang/Rendah |
| **Akurasi/Confidence** | Ditampilkan (%) | Tidak ada |
| **Probabilitas** | Ditampilkan | Tidak ada |
| **DASS-21 Breakdown** | Depresi, Kecemasan, Stress | Tidak ada |
| **Severity Label** | Normal - Extremely Severe | Tidak ada |
| **Fallback System** | ✅ Ke persentase jika ML gagal | Tidak perlu |

---

## ✅ Verification

Sistem sudah di-test dan diverifikasi:

- ✅ Family Social Factor menggunakan ML prediction
- ✅ Self Efficacy menggunakan sistem biasa (persentase)
- ✅ Fallback mechanism bekerja dengan baik
- ✅ Tidak ada error saat ML API tidak tersedia
- ✅ Tampilan hasil sesuai dengan tipe kuisioner

---

## 📝 Catatan Penting

1. **ML API Opsional**: Sistem berjalan normal tanpa ML API
2. **Family Social Only**: ML hanya untuk tipe `family_social`
3. **Self Efficacy Biasa**: Menggunakan perhitungan persentase sederhana
4. **Data Terpisah**: Setiap kuisioner menyimpan data berbeda di database
