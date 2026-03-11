# api_model.py
from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import pandas as pd
import numpy as np

# Inisialisasi Flask - ini sudah benar, tidak perlu diisi apapun
app = Flask(__name__)
CORS(app)  # Mengizinkan akses dari domain lain

# Load model yang sudah dibuat
model_data = joblib.load('model_dass_predictor.pkl')
pipeline = model_data['pipeline']
le = model_data['label_encoder']
feature_names = model_data['feature_names']

@app.route('/predict', methods=['POST'])
def predict():
    try:
        # Ambil data JSON dari request
        data = request.get_json()
        
        # Konversi ke DataFrame
        input_data = pd.DataFrame([data])
        
        # Pastikan kolom sesuai urutan
        input_data = input_data[feature_names]
        
        # Prediksi
        prediction = pipeline.predict(input_data)
        probabilities = pipeline.predict_proba(input_data)
        
        # Konversi hasil
        pred_label = le.inverse_transform(prediction)[0]
        
        # Buat response
        response = {
            'success': True,
            'prediction': pred_label,
            'confidence': float(max(probabilities[0])),
            'probabilities': {
                label: float(prob)
                for label, prob in zip(le.classes_, probabilities[0])
            },
            'input_data': data
        }
        
        return jsonify(response)
    
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 400

@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        'status': 'healthy',
        'model_loaded': True,
        'features': feature_names
    })

if __name__ == '__main__':
    # Jalankan server di port 5000
    app.run(host='0.0.0.0', port=5000, debug=True)