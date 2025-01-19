from flask import Flask, request, jsonify, render_template
from flask_cors import CORS
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.neighbors import NearestNeighbors
import re

app = Flask(__name__)
CORS(app, resources={r"/get_recommendations": {"origins": "http://localhost"}})

df = pd.read_csv(r"C:\Users\acer\OneDrive - ums.edu.my (1)\Documents\data sc\fyp\recipes.csv")
df = df.drop(['RecipeId', 'AuthorId', 'AuthorName', 'DatePublished', 'Description', 'Images', 'ReviewCount',
              'AggregatedRating', 'RecipeYield', 'Keywords', 'RecipeServings', 'FatContent', 'SaturatedFatContent',
              'CholesterolContent', 'SodiumContent', 'CarbohydrateContent', 'FiberContent', 'SugarContent',
              'ProteinContent', ], axis=1)
df = df.dropna()


def transform_instructions(instructions):
    instructions_list = instructions.replace('c(', '').replace('.")', '.').replace(')', '').replace('"', '').split(',')
    instructions_list = [instruction.strip() for instruction in instructions_list]
    paragraph = ' '.join(instructions_list)
    return paragraph

def transform_ingredients(ingredients):
    ingredients_list = ingredients.replace('c(', '').replace(')', '').replace('"', '').split(',')
    ingredients_list = [ingredient.strip() for ingredient in ingredients_list]
    ingredient_string = ', '.join(ingredients_list)
    return ingredient_string

# Apply the transformation functions to the columns
df['RecipeInstructions'] = df['RecipeInstructions'].apply(transform_instructions)
df['RecipeIngredientParts'] = df['RecipeIngredientParts'].apply(transform_ingredients)

# add quantity for ingredients
def transform_ingredients_and_quantities(parts, quantities):
    parts_list = parts.replace('c(', '').replace(')', '').replace('"', '').split(',')
    quantities_list = quantities.replace('c(', '').replace(')', '').replace('"', '').split(',')

    parts_list = [part.strip() for part in parts_list]
    quantities_list = [quantity.strip() for quantity in quantities_list]

    ingredients_with_quantities = [f"{quantity} {part}" for part, quantity in zip(parts_list, quantities_list)]
    ingredient_string = ', '.join(ingredients_with_quantities)
    
    return ingredient_string

df['RecipeIngredients'] = df.apply(lambda x: transform_ingredients_and_quantities(x['RecipeIngredientParts'], x['RecipeIngredientQuantities']), axis=1)


# Function to convert PT24H20M format to minutes
def parse_duration(duration):
    hours = 0
    minutes = 0
    if 'H' in duration:
        hours = int(re.findall(r'(\d+)H', duration)[0])
    if 'M' in duration:
        minutes = int(re.findall(r'(\d+)M', duration)[0])
    return hours * 60 + minutes
df['prep_time_minutes'] = df['TotalTime'].apply(parse_duration)

def format_prep_time(minutes):
    hours = minutes // 60
    remaining_minutes = minutes % 60
    
    if hours == 0:
        return f"{remaining_minutes} minutes"
    elif remaining_minutes == 0:
        return f"{hours} hours"
    else:
        return f"{hours} hours {remaining_minutes} minutes"

def is_valid_input(ingredients_input):
    for ingredient in ingredients_input:
        if not ingredient.replace(",", "").replace(" ", "").isalpha():
            return False
    return True

@app.route('/get_recommendations', methods=['POST'])
def get_recommendations():
    try:
        # Get user input from the form fields
        data = request.json
        ingredients_input = data.get('recipe_ingredients', '').split(',')
        prep_time_input = int(data.get('recipe_preptime', 0))

        if not is_valid_input(ingredients_input):
            return jsonify({'error': 'Invalid input. Please enter alphabetic characters and commas only.'})


        df['TotalTime'] = df['prep_time_minutes'].apply(format_prep_time)

        # Filter dataset by user input
        df_filtered = df[df['prep_time_minutes'] <= prep_time_input]

        # Create a document-term matrix using the ingredients
        tfidf = TfidfVectorizer()
        tfidf_matrix = tfidf.fit_transform(df_filtered['RecipeIngredientParts'])

        # Fit the kNN model
        k = 15  
        knn_model = NearestNeighbors(n_neighbors=k, metric='cosine')
        knn_model.fit(tfidf_matrix)

        # Vectorize user input ingredients
        user_ingredients = tfidf.transform([','.join(ingredients_input)])

        # Find k nearest neighbors
        distances, indices = knn_model.kneighbors(user_ingredients)

        # Transform the recommendations into a list of dictionaries
        recommendations = []
        for idx in indices[0]:
            recipe = df_filtered.iloc[idx]
            recommendation = {
                'title': recipe['Name'],
                'ingredients': recipe['RecipeIngredients'],
                'prep_time': recipe['TotalTime'],
                'calories': recipe['Calories'],
                'instructions': recipe['RecipeInstructions'],
                'category': recipe['RecipeCategory']
            }
            recommendations.append(recommendation)

        return jsonify(recommendations)
    except Exception as e:
        return jsonify({'error': str(e)})

if __name__ == "__main__":
    app.run(host="127.0.0.1", port=5000, debug=True)
