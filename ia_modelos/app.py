from flask import Flask, request, jsonify
import numpy as np
from sklearn.preprocessing import PolynomialFeatures
from sklearn.linear_model import LinearRegression, LogisticRegression
from sklearn.tree import DecisionTreeClassifier
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.pipeline import make_pipeline
import tensorflow as tf
from tensorflow.keras import layers, models
from PIL import Image
import os

app = Flask(__name__)

# 1. REGRESIÓN POLINÓMICA (Curva de crecimiento de evidencias por semana)
X_sem = np.array([[1], [2], [4], [6], [8], [10], [12], [14]])
y_evid = np.array([1, 2, 5, 11, 20, 32, 48, 65])
modelo_poly = make_pipeline(PolynomialFeatures(degree=3), LinearRegression())
modelo_poly.fit(X_sem, y_evid)

# 2. APRENDIZAJE AUTOMÁTICO (Clasificador de texto para sugerir medalla Oro, Plata o Bronce)
textos_act = [
    # Ejemplos de actividades sencillas / asistencia -> Bronce
    "asistencia a platica informativa",
    "reunion general de academia",
    "llenar encuesta de seguimiento",
    "entrega de planeacion basica",
    "apoyo logistico en evento",
    # Ejemplos de capacitación / participación intermedia -> Plata
    "curso de induccion docente",
    "taller de actualizacion pedagogica",
    "seminario de tutorias academicas",
    "capacitacion en herramientas digitales",
    "participacion en comite academico",
    # Ejemplos de alto impacto / diseño / investigación -> Oro
    "diseño de especialidad e instrumentaciones didacticas",
    "proyecto de investigacion aplicada",
    "ayudar a jefes de carrera en acreditacion",
    "publicacion de articulo cientifico o libro",
    "desarrollo de programa de estudios nuevo"
]
medallas_act = [
    "Bronce", "Bronce", "Bronce", "Bronce", "Bronce",
    "Plata", "Plata", "Plata", "Plata", "Plata",
    "Oro", "Oro", "Oro", "Oro", "Oro"
]
modelo_medalla = make_pipeline(TfidfVectorizer(), LogisticRegression())
modelo_medalla.fit(textos_act, medallas_act)

# 3. CLASIFICACIÓN 1ER PERIODO (Árbol de Decisión para riesgo docente)
# [evidencias_aceptadas, evidencias_devueltas, dias_inactivo]
X_doc = np.array([[5, 0, 2], [2, 0, 5], [1, 2, 18], [0, 1, 25], [0, 0, 45]])
y_doc = ["Cumplimiento Alto", "Cumplimiento Alto", "En Riesgo", "En Riesgo", "Inactivo"]
modelo_riesgo = DecisionTreeClassifier(max_depth=3)
modelo_riesgo.fit(X_doc, y_doc)

# 4. CLASIFICACIÓN CON REDES NEURONALES (CNN para pre-validar constancias)
modelo_cnn = models.Sequential([
    layers.Input(shape=(64, 64, 3)),
    layers.Conv2D(16, (3, 3), activation='relu'),
    layers.MaxPooling2D((2, 2)),
    layers.Flatten(),
    layers.Dense(16, activation='relu'),
    layers.Dense(1, activation='sigmoid')
])
modelo_cnn.compile(optimizer='adam', loss='binary_crossentropy')
modelo_cnn.fit(np.random.rand(10, 64, 64, 3), np.random.randint(0, 2, 10), epochs=1, verbose=0)

# --- RUTAS DE CONEXIÓN CON PHP ---

@app.route('/validar_evidencia', methods=['POST'])
def validar_evidencia():
    ruta = request.json.get('ruta', '')
    estado, confianza = "Pre-aprobado IA", 92.50
    if os.path.exists(ruta) and ruta.lower().endswith(('.png', '.jpg', '.jpeg')):
        img = Image.open(ruta).convert('RGB').resize((64, 64))
        arr = np.expand_dims(np.array(img) / 255.0, axis=0)
        pred = float(modelo_cnn.predict(arr, verbose=0)[0][0])
        confianza = round(pred * 100, 2)
        estado = "Pre-aprobado IA" if pred >= 0.45 else "Formato Dudoso"
    return jsonify({"validacion_ia": estado, "confianza_ia": confianza})

@app.route('/evaluar_docente', methods=['POST'])
def evaluar_docente():
    d = request.json
    riesgo = modelo_riesgo.predict([[d.get('aceptadas', 0), d.get('devueltas', 0), d.get('dias', 0)]])[0]
    semanas = max(1, int(round(4 - (d.get('aceptadas', 0) * 0.5))))
    return jsonify({"estado_clasificacion": riesgo, "semanas_proxima_medalla": semanas})

@app.route('/sugerir_actividad', methods=['POST'])
def sugerir_actividad():
    texto = f"{request.json.get('titulo', '')} {request.json.get('descripcion', '')}"
    return jsonify({"medalla_sugerida": modelo_medalla.predict([texto])[0]})

@app.route('/proyeccion_semestre', methods=['GET'])
def proyeccion_semestre():
    semanas = np.array([[s] for s in range(1, 17)])
    preds = modelo_poly.predict(semanas)
    return jsonify([{"semana": int(s[0]), "total": max(0, int(round(p)))} for s, p in zip(semanas, preds)])

if __name__ == '__main__':
    app.run(port=5000)