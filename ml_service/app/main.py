# app/main.py
from fastapi import FastAPI
from app.routers import sentiment_router, risk_router # Tambahkan risk_router

# Inisialisasi aplikasi FastAPI
app = FastAPI(
    title="Smart Support CS ML API",
    description="Service Machine Learning untuk website support CS",
    version="1.0"
)

# Daftarkan router untuk analisis sentimen dan risiko
app.include_router(sentiment_router.router)
app.include_router(risk_router.router) # Daftarkan router risiko

# Endpoint dasar untuk cek status server
@app.get("/")
def home():
    return {"message": "Smart Support CS ML API is running!"}