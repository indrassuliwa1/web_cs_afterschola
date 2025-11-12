# train_risk_model.py
# Melatih model klasifikasi risiko pembayaran (Risk Prediction)
# Output: models/risk_model.pkl

import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import classification_report
import joblib
import os
import numpy as np

# 1️⃣ Dataset Contoh (Skala disesuaikan: Harga diukur dalam JUTAAN RUPIAH)
# Contoh: 0.5 = Rp 500.000, 3.0 = Rp 3.000.000, 10.0 = Rp 10.000.000
# Target: risiko (0 = Rendah/Aman, 1 = Tinggi/Terlambat)
data = {
    # Data disesuaikan ke skala Ratusan Ribu hingga Maks 10 Juta
    'harga_kontrak_jt': [0.5, 1.0, 0.2, 1.5, 0.7, 3.0, 0.1, 2.0, 4.0, 0.3,
                         5.0, 10.0, 2.5, 4.5, 1.0, 3.5, 0.4, 1.2,
                         6.0, 8.0, 15.0], # Menambahkan beberapa nilai tinggi
    'durasi_bulan':     [6, 12, 3, 9, 6, 12, 1, 6, 9, 3,
                         12, 12, 9, 12, 3, 6, 1, 6,
                         12, 9, 12],
    'tipe_pendaftar': ['guru', 'orangtua', 'siswa', 'guru', 'orangtua', 'guru', 'siswa', 'orangtua', 'guru', 'siswa',
                         'orangtua', 'guru', 'guru', 'orangtua', 'siswa', 'orangtua', 'guru', 'siswa',
                         'guru', 'orangtua', 'siswa'],
    'riwayat_terlambat': [0, 0, 0, 0, 0, 1, 0, 0, 1, 0,
                          1, 1, 0, 1, 0, 0, 0, 0,
                          1, 1, 2],
    # Target Risiko: Nilai >= 5 JT atau durasi panjang + riwayat terlambat = Risiko Tinggi
    'target_risiko':    [0, 0, 0, 0, 0, 1, 0, 0, 0, 0, # Bobot Risiko Rendah
                         1, 1, 0, 1, 0, 0, 0, 0, # Bobot Risiko Tinggi (untuk harga >= 5 JT)
                         1, 1, 1] 
}
df = pd.DataFrame(data)

# 2️⃣ Feature Engineering dan Encoding
# Menggunakan LabelEncoder untuk tipe_pendaftar
le = LabelEncoder()
df['tipe_encoded'] = le.fit_transform(df['tipe_pendaftar'])

# Fitur yang akan digunakan
features = ['harga_kontrak_jt', 'durasi_bulan', 'riwayat_terlambat', 'tipe_encoded']
X = df[features]
y = df['target_risiko']

# 3️⃣ Split dan Latih Model
# Ukuran data test disesuaikan
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Menggunakan Model Klasifikasi Sederhana
model = LogisticRegression(solver='liblinear', random_state=42)
model.fit(X_train, y_train)

# 4️⃣ Evaluasi (Opsional)
y_pred = model.predict(X_test)
print("--- Laporan Klasifikasi Risiko ---")
if len(np.unique(y_test)) > 1:
    print(classification_report(y_test, y_pred, zero_division=0))
else:
    print("Tidak cukup data unik untuk laporan klasifikasi yang valid.")

# 5️⃣ Simpan Model dan Encoder
if not os.path.exists('models'):
    os.makedirs('models')
    
metadata = {
    'model': model,
    'encoder': le,
    'features': features
}
joblib.dump(metadata, 'models/risk_model.pkl')
print("✅ Model Risiko berhasil disimpan ke models/risk_model.pkl")