#!/usr/bin/env python
# coding: utf-8



from flask import Flask, request, jsonify
from flask_cors import CORS  # Import CORS from flask_cors
import real

app = Flask(__name__)
CORS(app, resources={r"/get_recommendations": {"origins": "http://localhost"}})
#CORS(app, resources={r"/*": {"origins": "http://localhost/testform/foodrec.php", "methods": ["GET", "POST"], "allow_headers": ["Content-Type"]}})
#CORS(app, resources={r"/*": {"origins": "http://localhost/testform/foodrec.php"}})

@app.route('/get_recommendations', methods=['POST'])
def get_recommendations():
    print("Received a request to /get_recommendations")
    data = request.get_json()
    
    print("Received a request to /get_recommendations") 
    # Get input from the user
    ingredients = request.form.get('recipe_ingredients').split(',')
    prep_time = int(request.form.get('recipe_preptime'))

    # Call your machine learning code from real.py
    recommendations = real.get_recommendations(ingredients, prep_time)

    # Format recommendations as a list of dictionaries
    formatted_recommendations = []
    for recommendation in recommendations:
        formatted_recommendation = {
            'title': recommendation['Name'],
            'ingredients': recommendation['RecipeIngredientParts'],
            'prep_time': recommendation['TotalTime'],
            'calories': recommendation['Calories'],
            'instructions': recommendation['RecipeInstructions'],
        }
        formatted_recommendations.append(formatted_recommendation)

    return jsonify(formatted_recommendations)

@app.route('/get_recommendations', methods=['OPTIONS'])
def handle_options():
    response = jsonify()
    response.headers.add('Access-Control-Allow-Origin', 'http://localhost/testform/foodrec.php')
    response.headers.add('Access-Control-Allow-Headers', 'Content-Type')
    response.headers.add('Access-Control-Allow-Methods', 'POST')
    return response

if __name__ == "__main__":
    app.run(host="127.0.0.1", port=5000, debug=True)
