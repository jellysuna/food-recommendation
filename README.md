# Food Recommendation System

## Overview

This system provides a meal planning solution, helping users find food recommendations based on their available ingredients and available time to cook. It includes both **User** and **Admin** sections, where users can interact with the system for personalized food recommendations and calorie/water tracking.

## Recommendation Algorithm

The recommendation system uses **Content-Based Filtering** and **K-Nearest Neighbors (KNN)** with a **Cosine Similarity** metric to recommend recipes based on user input.
The algorithm works by filtering recipes based on the user’s available prep time before applying TF-IDF and KNN to identify the closest matches, finally returning the top 15 recommended recipes.

### Process:
1. **Data Preprocessing**: The dataset is cleaned by removing unnecessary attributes and transforming attributes into a usable format.
2. **KNN with Cosine Similarity**: A **TF-IDF Vectorizer** is applied to create a document-term matrix from the recipe ingredients.
   This matrix is then used with the **K-Nearest Neighbors (KNN)** algorithm, where **Cosine Similarity** is used as the metric to measure the similarity between the user’s input and the recipes.


### Files:
- **recommendation.py**: Contains the main algorithm to generate food recommendations (Flask-based).
- **100test.py**: A separate testing file to check the algorithm's accuracy (100 runs to get average accuracy) and compute metrics like precision and recall.


## Data

### Data Source
The dataset `recipes.csv` is **not included** in the repository and can be downloaded from [here](https://www.kaggle.com/datasets/irkaal/foodcom-recipes-and-reviews).

### Data Configuration
The path to `recipes.csv` is set in the `config.py` file. Update the `DATA_PATH` variable to point to the correct location of the dataset.

### Database Configuration (config.php)
The database connection settings are managed in `config.php`.

## Technologies Used
- HTML
- CSS
- JavaScript
- PHP
- MySQL
- Python
- Flask
