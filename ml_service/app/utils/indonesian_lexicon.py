# ml_service/app/utils/indonesian_lexicon.py

# Daftar kata-kata sentimen Bahasa Indonesia yang lebih kaya untuk Rule-Based Analysis.
# Score: Positif (1.0 hingga 4.0), Negatif (-1.0 hingga -4.0)

LEXICON = {
    # POSITIVE WORDS (1.0 to 4.0)
    'senang': 2.0,
    'terima kasih': 3.0,
    'terbaik': 3.5,
    'sukses': 2.5,
    'puas': 3.0,
    'mantap': 2.8,
    'luar biasa': 4.0,
    'bagus': 2.0,
    'hebat': 3.0,
    'profesional': 2.5,
    'membantu': 1.8,
    'solusi': 1.5,
    'bersih': 1.0,
    'nyaman': 1.5,
    'menyenangkan': 3.0,
    'cepat': 1.0,
    'ramah': 2.0,
    'berkualitas': 2.5,
    'sangat baik': 3.5,
    'rekomendasi': 2.0,
    'terjamin': 1.5,
    'yakin': 1.0,
    'canggih': 1.0,
    'efektif': 1.5,
    'fantastis': 3.0,
    'keren': 2.0,
    'oke': 1.0,
    'top': 3.0,

    # NEGATIVE WORDS (-1.0 to -4.0)
    'buruk': -3.0,
    'lambat': -1.5,
    'kecewa': -3.5,
    'mahal': -2.0,
    'gagal': -3.0,
    'masalah': -1.5,
    'rusak': -2.5,
    'sulit': -1.0,
    'komplain': -1.0,
    'parah': -4.0,
    'mengecewakan': -3.0,
    'tidak sesuai': -2.5,
    'jelek': -3.0,
    'kurang': -1.5,
    'rugi': -2.0,
    'payah': -3.5,
    'batal': -1.0,
    'susah': -1.0,
    'tidak jelas': -2.0,
    'rumit': -1.0,

    # NEUTRAL WORDS (0.0 to 0.5) - Digunakan untuk memoderasi intensitas.
    'cukup': 0.5,
    'hanya': 0.0,
    'sedikit': 0.0,
    'namun': 0.0,
    'tapi': 0.0,
    'biasa': 0.0,
    'mungkin': 0.0,
    'agak': -0.5,
}

# Emoticon / Acronyms (Opsional, tapi bagus untuk memperkaya)
EMOTICON_LEXICON = {
    ':)': 2.5,
    '(:': 2.5,
    ':d': 3.0,
    ':p': 1.0,
    ';)': 1.5,
    'xD': 3.5,
    'wkwk': 1.5,
    'wkwkwk': 2.0,
    
    # Negative emoticons
    ':(': -2.5,
    '):': -2.5,
    'd:': -3.0,
    'x(': -3.5,
    'hiks': -2.0,
}

# Intensifiers (Kata penguat/pelemah sentimen)
# Contoh: "sangat bagus" -> memperkuat skor "bagus"
INTENSIFIERS = {
    'sangat': 2.0,  # Menggandakan skor kata berikutnya
    'sekali': 1.5,
    'banget': 1.8,
    'amat': 1.5,
    'terlalu': 1.0,
}