#!/usr/bin/env python
# coding: utf-8

# In[1]:


import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import ingredient_parser
import pickle
import config
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.neighbors import NearestNeighbors
import unidecode, ast
import re
from sklearn.preprocessing import LabelEncoder
from scipy.sparse import hstack

df = pd.read_csv(r"C:\Users\acer\OneDrive - ums.edu.my (1)\Documents\data sc\fyp\recipes.csv")
df = df.drop(['AuthorId', 'AuthorName', 'DatePublished', 'Description', 'Images', 'ReviewCount',
                'AggregatedRating', 'RecipeYield', 'Keywords', 'RecipeServings'], axis=1)
df = df.dropna()


# Prompt user for input
ingredients_input = input("Enter comma separated list of ingredients: ").split(',')
prep_time_input = int(input("Enter preparation time in minutes: "))

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

# Filter dataset by user input
df = df[df['prep_time_minutes'] <= prep_time_input]
    
# Create a document-term matrix using the ingredients
tfidf = TfidfVectorizer()
tfidf_matrix = tfidf.fit_transform(df['RecipeIngredientParts'])
#ingredient_matrix = tfidf.fit_transform(df['RecipeIngredientParts'])

# Fit the kNN model
k = 30  
knn_model = NearestNeighbors(n_neighbors=k, metric='cosine')
knn_model.fit(tfidf_matrix)

# Vectorize user input ingredients
user_ingredients = tfidf.transform([','.join(ingredients_input)])

# Find k nearest neighbors
distances, indices = knn_model.kneighbors(user_ingredients)

def transform_preparation_time(time_str):
    
    # Remove the 'PT' prefix and extract the numeric portion
    numeric_str = re.sub(r'[^0-9]+', '', time_str)
    
    # Extract the hours and minutes from the numeric string
    hours = int(numeric_str[:-2]) if numeric_str[:-2] else 0
    minutes = int(numeric_str[-2:]) if numeric_str[-2:] else 0
    
    if hours == 0:
        return f"{minutes} minutes"
    else:
        return f"{hours} hours {minutes} minutes"

df['TotalTime'] = df['TotalTime'].apply(transform_preparation_time)

def transform_instructions(instructions):
    # Convert the string representation to a list
    instructions_list = instructions.replace('c(', '').replace('.")', '.').replace(')', '').replace('"', '').split(',')
    instructions_list = [instruction.strip() for instruction in instructions_list]
    
    # Combine instructions into a paragraph
    paragraph = ' '.join(instructions_list)
    
    return paragraph


df['RecipeInstructions'] = df['RecipeInstructions'].apply(transform_instructions)


def transform_ingredients(ingredients):
    # Convert the string representation to a list
    ingredients_list = ingredients.replace('c(', '').replace(')', '').replace('"', '').split(',')
    ingredients_list = [ingredient.strip() for ingredient in ingredients_list]
    
    # Combine ingredients into a string
    ingredient_string = ', '.join(ingredients_list)
    
    return ingredient_string


df['RecipeIngredientParts'] = df['RecipeIngredientParts'].apply(transform_ingredients)


# Display recommended recipes
print("Recommended Recipes:")
print("------------------------")
for idx in indices[0]:
    recipe = df.iloc[idx]
    print("Recipe Title:", recipe['Name'])
    print("Recipe ID:", recipe['RecipeId'])
    print("Ingredients:", recipe['RecipeIngredientParts'])
    print("Preparation Time:", recipe['TotalTime'])
    print("Calories:", recipe['Calories'])
    print("Instruction:", recipe['RecipeInstructions'])
    print("Category:", recipe['RecipeCategory'])
    print("------------------------")





