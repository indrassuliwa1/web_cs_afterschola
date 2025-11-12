import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.svm import LinearSVC
from sklearn.pipeline import Pipeline
from sklearn.metrics import classification_report
import joblib
import os

# Pastikan direktori models ada
os.makedirs('models', exist_ok=True)

# 1️⃣ Contoh dataset (bisa diganti file CSV kamu)
data = {
    "text": [
        "saya sangat senang belajar di sini",
        "pelayanan buruk dan lambat",
        "kursusnya bagus banget!",
        "saya kecewa dengan pengajarnya",
        "materinya biasa saja",
        "tempatnya nyaman dan bersih",
        "harga terlalu mahal",
        "pengalaman menyenangkan sekali",
        "tidak sesuai harapan",
        "terbaik dan profesional"
    ],
    "label": [
        "positive",
        "negative",
        "positive",
        "negative",
        "neutral",
        "positive",
        "negative",
        "positive",
        "negative",
        "positive"
    ]
}

df = pd.DataFrame(data)

# 2️⃣ Split data
X_train, X_test, y_train, y_test = train_test_split(df['text'], df['label'], test_size=0.2, random_state=42)

# 3️⃣ Buat pipeline
model = Pipeline([
    ('tfidf', TfidfVectorizer(max_features=5000, ngram_range=(1,2))),
    ('clf', LinearSVC())
])

# 4️⃣ Latih model
model.fit(X_train, y_train)

# 5️⃣ Evaluasi cepat
y_pred = model.predict(X_test)
print("--- Laporan Klasifikasi Sentimen ---")
print(classification_report(y_test, y_pred, zero_division=0))

# 6️⃣ Simpan model
joblib.dump(model, 'models/sentiment_model.pkl')
print("✅ Model berhasil disimpan ke models/sentiment_model.pkl")