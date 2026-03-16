from flask import Flask, request, jsonify
import joblib
import pandas as pd

app = Flask(__name__)

# Load model
model = joblib.load("model_svm.pkl")

@app.route("/api/health", methods=["GET"])
def health():
    return jsonify({"status": "Flask API running"})


@app.route("/api/predict", methods=["POST"])
def predict():

    data = request.json

    feature_order = [
        "SE1","SE2","SE3","SE4","SE5",
        "SE6","SE7","SE8","SE9","SE10",
        "Autonomy",
        "Environmental_Mastery",
        "Personal_Growth",
        "Positive_Relations",
        "Purpose_in_Life",
        "Self_Acceptance"
    ]

    df = pd.DataFrame([data])
    df = df[feature_order]

    prediction = model.predict(df)[0]

    if prediction == 1:
        label = "high_well_being"
        explanation = "Mahasiswa memiliki psychological well-being yang baik."
    else:
        label = "low_well_being"
        explanation = "Mahasiswa memiliki psychological well-being yang rendah."

    return jsonify({
        "prediction": label,
        "explanation": explanation
    })


if __name__ == "__main__":
    app.run(debug=True)