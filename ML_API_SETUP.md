# Setup ML API untuk DASS-21 Prediction

## Persyaratan
- Python 3.8+
- Flask
- Scikit-learn
- Pandas
- NumPy

## Cara Menjalankan ML API

### 1. Install Dependencies
```bash
pip install flask flask-cors scikit-learn pandas numpy joblib
```

### 2. Buat File ML API (ml_api.py)

```python
from flask import Flask, request, jsonify
from flask_cors import CORS
import numpy as np

app = Flask(__name__)
CORS(app)

# Load model jika sudah ada
# model = joblib.load('dass_model.pkl')

@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        
        # Extract features
        depression_score = float(data.get('Depression_Score', 0))
        anxiety_score = float(data.get('Anxiety_Score', 0))
        stress_score = float(data.get('Stress_Score', 0))
        so_score = float(data.get('SO_Score', 0))
        family_score = float(data.get('Family_Score', 0))
        friends_score = float(data.get('Friends_Score', 0))
        
        # Prepare features for prediction
        features = np.array([[
            depression_score,
            anxiety_score,
            stress_score,
            so_score,
            family_score,
            friends_score
        ]])
        
        # TODO: Load dan gunakan model ML yang sudah dilatih
        # prediction = model.predict(features)[0]
        # probabilities = model.predict_proba(features)[0]
        
        # Contoh response (dummy prediction)
        # Ganti dengan model ML yang sebenarnya
        max_score = max(depression_score, anxiety_score, stress_score)
        
        if max_score >= 28:
            prediction = 'Depression'
        elif max_score >= 20:
            prediction = 'Anxiety'
        elif max_score >= 15:
            prediction = 'Stress'
        else:
            prediction = 'Normal'
        
        # Dummy probabilities
        probabilities = {
            'Normal': 0.1,
            'Depression': 0.3 if prediction == 'Depression' else 0.1,
            'Anxiety': 0.3 if prediction == 'Anxiety' else 0.1,
            'Stress': 0.3 if prediction == 'Stress' else 0.1
        }
        
        # Normalize probabilities
        total = sum(probabilities.values())
        probabilities = {k: v/total for k, v in probabilities.items()}
        
        confidence = max(probabilities.values())
        
        return jsonify({
            'prediction': prediction,
            'confidence': confidence,
            'probabilities': probabilities
        })
        
    except Exception as e:
        return jsonify({
            'error': str(e)
        }), 500

if __name__ == '__main__':
    print("Starting ML API server on http://localhost:5000")
    app.run(host='0.0.0.0', port=5000, debug=True)
```

### 3. Jalankan ML API
```bash
python ml_api.py
```

### 4. Test API
```bash
curl -X POST http://localhost:5000/predict \
  -H "Content-Type: application/json" \
  -d '{
    "Depression_Score": 10,
    "Anxiety_Score": 8,
    "Stress_Score": 15,
    "SO_Score": 4.2,
    "Family_Score": 4.2,
    "Friends_Score": 4.2
  }'
```

### 5. Konfigurasi Laravel
Tambahkan ke file `.env`:
```
ML_API_URL=http://localhost:5000/predict
```

## Fallback System
Jika ML API tidak tersedia, sistem akan otomatis menggunakan perhitungan berdasarkan persentase skor.

## Catatan
- ML API harus berjalan sebelum user mengisi kuisioner
- Pastikan port 5000 tidak digunakan oleh aplikasi lain
- Untuk production, gunakan model ML yang sudah dilatih dengan data yang cukup
