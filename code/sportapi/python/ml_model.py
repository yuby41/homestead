import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
import pickle

# Cargar los datos desde el CSV exportado de Laravel
data = pd.read_csv('../storage/app/private/data/manual_matches.csv')

# Preprocesar los datos (eliminar filas con datos faltantes)
data = data.dropna()

# Definir características (X) y variable objetivo (y)
X = data[['home_odds', 'away_odds', 'draw_odds']]
y = np.where(data['home_score'] > data['away_score'], 1, 0)  # 1 = gana local, 0 = pierde/empate

# Dividir en conjunto de entrenamiento y prueba
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Entrenar el modelo
model = RandomForestClassifier(n_estimators=100, random_state=42)
model.fit(X_train, y_train)

# Guardar el modelo entrenado
with open('model.pkl', 'wb') as file:
    pickle.dump(model, file)

print("Modelo entrenado y guardado exitosamente")
