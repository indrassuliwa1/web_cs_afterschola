from fastapi import APIRouter, HTTPException
from pydantic import BaseModel
from app.utils.indonesian_lexicon import LEXICON, INTENSIFIERS # Import LEXICON dan INTENSIFIERS
import os
import re # Diperlukan untuk regex cleaning

router = APIRouter(prefix="/sentiment", tags=["Sentiment"])

# Karena ini Rule-Based, kita tidak perlu memuat model.pkl
# Model adalah logic scoring di bawah.

class SentimentInput(BaseModel):
    text: str

def analyze_sentiment(text: str):
    """
    Menganalisis sentimen menggunakan Rule-Based dan Lexicon Bahasa Indonesia.
    """
    # 1. Cleaning Teks (Hapus non-alphanumeric, kecuali spasi)
    text = text.lower()
    text = re.sub(r'[^a-z0-9\s]', '', text) 

    words = text.split()
    total_score = 0.0
    
    # Track intensifier (penanda kata penguat)
    intensifier_factor = 1.0 

    for word in words:
        score = LEXICON.get(word, 0.0)
        
        # 2. Terapkan Intensifier (jika kata sebelumnya adalah kata penguat)
        if score != 0.0:
            total_score += score * intensifier_factor
            intensifier_factor = 1.0 # Reset factor setelah skor diterapkan
        
        # 3. Cek apakah kata saat ini adalah Intensifier
        elif word in INTENSIFIERS:
            # Atur faktor penguatan untuk kata sentimen berikutnya
            intensifier_factor = INTENSIFIERS[word]
            
        # 4. Jika kata tidak ada di LEXICON dan bukan INTENSIFIER, abaikan (skor 0)
        else:
            intensifier_factor = 1.0 # Reset just in case

    # 5. Klasifikasi Berdasarkan Skor Total
    if total_score > 0.5:
        sentiment = "positive"
    elif total_score < -0.5:
        sentiment = "negative"
    else:
        sentiment = "neutral"
        
    return sentiment, total_score

@router.post("/predict")
def predict_sentiment(data: SentimentInput):
    try:
        sentiment, score = analyze_sentiment(data.text)
        
        return {
            "input_text": data.text,
            "predicted_sentiment": sentiment,
            "sentiment_score": score, # Tampilkan skor untuk debugging
            "status": "OK"
        }
    except Exception as e:
        # Jika terjadi error saat memproses teks
        raise HTTPException(status_code=500, detail=f"Gagal melakukan analisis sentimen: {str(e)}")