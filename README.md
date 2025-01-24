# Food Recommendation System

## Overview

This system provides a meal planning solution, helping users find food recommendations based on their available ingredients and available time to cook. It includes both **User** and **Admin** sections, where users can interact with the system for personalized food recommendations and calorie/water tracking.

## Features

### User Section
- **Meal Recommendations**: Users can get meal recommendations based on two inputs: ingredients they have and their available prep time. It generates 15 meal recommendations with details such as prep time, ingredients, instructions, and calorie information.
- **Ratings & Feedback**: Users can rate each recommended meal (1-5 scale).
- **Save & History**: Users can save their favorite meals or add them to their history with personalized notes.
- **Profile Management**: Users can update their profile (age, gender, weight, height, activity level) to calculate their daily calorie requirements.
- **Calorie Tracker**: Users can track their calorie intake by adding meal names and calorie values. The system will calculate the remaining calories needed for the day.
- **Water Intake Tracker**: Users can monitor their daily water intake (fixed at 2000ml) and update progress accordingly.
- **Grocery List**: Users can list ingredients they don’t have, with options to adjust quantity and mark items as bought.
- **Food Journal**: Users can record their eating behaviour or meal planning.
- **Feedback**: Users can provide feedback or report issues related to the system or recipes.

### Admin Section
- **Recipe Management**: Admins can add new recipes to the system.
- **Feedback Review**: Admins can view user feedback submitted via the feedback section.

## Data

### Data Source
The dataset `recipes.csv` is **not included** in the repository and can be downloaded from [here](https://www.kaggle.com/datasets/irkaal/foodcom-recipes-and-reviews).

### Data Configuration
The path to `recipes.csv` is set in the `config.py` file. Update the `DATA_PATH` variable to point to the correct location of the dataset.

### Database Configuration (config.php)
The database connection settings are managed in `config.php`.

## Recommendation Algorithm

The recommendation system uses **Content-Based Filtering** and **K-Nearest Neighbors (KNN)** with a **Cosine Similarity** metric to recommend recipes based on user input.

### Process:
1. **Data Preprocessing**: Clean the dataset by removing unnecessary attributes and changing formats.
2. **Cosine Similarity**: Measures the similarity between recipe profiles (based on attributes) and user profiles (based on ingredients).
3. **KNN**: Uses KNN to find the 15 most similar recipes to the user input.

### Files:
- **recommendation.py**: Contains the main algorithm to generate food recommendations (Flask-based).
- **100test.py**: A separate testing file to check the algorithm's accuracy (100 runs to get average accuracy) and compute metrics like precision and recall.

