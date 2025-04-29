from flask import Flask, request, jsonify
import pickle
import numpy as np

# Cargar el modelo entrenado
with open('model.pkl', 'rb') as file:
    model = pickle.load(file)

app = Flask(__name__)

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()
    home_odds = float(data['home_odds'])
    away_odds = float(data['away_odds'])
    draw_odds = float(data['draw_odds'])

    prediction = model.predict(np.array([[home_odds, away_odds, draw_odds]]))

    result = "Gana Local" if prediction[0] == 1 else "Empate o Gana Visitante"
    
    return jsonify({'prediction': result})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
