# Cara Aktivasi Virtual Environment Python

## Untuk Windows

### Method 1: Menggunakan PowerShell
```powershell
# Masuk ke directory project
cd D:\Skripsi\project

# Aktivasi virtual environment
.venv\Scripts\Activate.ps1

# Jika muncul error about execution policy, jalankan:
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope Process
# Lalu ulangi aktivasi
.venv\Scripts\Activate.ps1
```

### Method 2: Menggunakan Command Prompt (cmd)
```cmd
# Masuk ke directory project
cd D:\Skripsi\project

# Aktivasi virtual environment
.venv\Scripts\activate.bat
```

### Method 3: Menggunakan Git Bash
```bash
# Masuk ke directory project
cd /d/Skripsi/project

# Aktivasi virtual environment
source .venv/Scripts/activate
```

## Setelah Aktivasi

Setelah virtual environment aktif, Anda akan melihat `(.venv)` di prompt:

```powershell
(.venv) PS D:\Skripsi\project>
```

### Install Dependencies (jika belum)
```bash
pip install flask flask-cors scikit-learn pandas numpy joblib
```

### Jalankan ML API
```bash
python ml_api.py
```

## Keluar dari Virtual Environment

```powershell
deactivate
```

---

## Solusi Alternatif: Tanpa Virtual Environment

Jika tidak ingin menggunakan virtual environment:

1. Install packages langsung:
```bash
pip install flask flask-cors scikit-learn pandas numpy joblib
```

2. Jalankan ML API:
```bash
python ml_api.py
```

---

## Troubleshooting

### Error: "Running scripts is disabled on this system"
```powershell
# Temporarily allow script execution for current session
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope Process

# Then activate
.venv\Scripts\Activate.ps1
```

### Error: "No module named 'flask'"
```bash
# Pastikan virtual environment sudah aktif
pip install flask flask-cors scikit-learn pandas numpy joblib
```

### Error: Port 5000 already in use
```bash
# Cari proses yang menggunakan port 5000
netstat -ano | findstr :5000

# Kill proses tersebut (ganti PID dengan nomor proses)
taskkill /PID <PID> /F
```

---

## Catatan Penting

- ML API hanya diperlukan untuk **Family Social Factor** quiz
- Sistem akan berjalan normal **tanpa** ML API (fallback mechanism)
- Untuk testing, Anda bisa langsung menggunakan aplikasi tanpa ML API
