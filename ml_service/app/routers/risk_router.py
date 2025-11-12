# app/routers/risk_router.py
from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
import joblib
import os
import pandas as pd

router = APIRouter(prefix="/risk", tags=["Risk Prediction"])

# --- Load Model (Sekali saat startup) ---
MODEL_PATH = os.path.join(os.getcwd(), 'models', 'risk_model.pkl')
risk_model = None
risk_encoder = None
RISK_FEATURES = []

if os.path.exists(MODEL_PATH):
    try:
        metadata = joblib.load(MODEL_PATH)
        risk_model = metadata['model']
        risk_encoder = metadata['encoder']
        RISK_FEATURES = metadata['features']
        print(f"✅ Model Risiko berhasil dimuat dari {MODEL_PATH}")
    except Exception as e:
        print(f"❌ GAGAL memuat model risiko: {e}")
        
if risk_model is None:
    print("❌ PERINGATAN: Model risiko TIDAK DITEMUKAN atau gagal dimuat.")

# Input data dari Laravel (harus sama persis dengan fitur pelatihan)
class RiskInput(BaseModel):
    harga_kontrak_jt: float
    durasi_bulan: int
    riwayat_terlambat: int
    tipe_pendaftar: str # guru, orangtua, siswa

@router.post("/predict")
def predict_risk(data: RiskInput):
    if risk_model is None:
        raise HTTPException(status_code=503, detail="Layanan Prediksi Risiko sedang tidak tersedia (Model tidak dimuat).")
    
    try:
        # 1. Konversi data input ke DataFrame
        input_data = pd.DataFrame([data.model_dump()])

        # 2. Encoding fitur kategorikal (tipe_pendaftar) menggunakan encoder yang sudah dilatih
        input_data['tipe_encoded'] = risk_encoder.transform(input_data['tipe_pendaftar'])
        
        # 3. Pilih fitur sesuai urutan saat pelatihan
        X = input_data[RISK_FEATURES]

        # 4. Prediksi probabilitas (Skor Risiko: Probabilitas risiko = 1)
        # Probabilitas 0-1, di mana 1 = risiko tinggi
        risk_score = risk_model.predict_proba(X)[:, 1][0]
        
        # 5. Klasifikasi risiko (Threshold 0.5)
        risk_class = "Tinggi" if risk_score >= 0.5 else "Rendah"
        
        return {
            "status": "success",
            "risk_score": round(risk_score, 4), # Dibulatkan
            "risk_prediction": risk_class,
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Gagal memprediksi risiko: {str(e)}")