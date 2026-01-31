-- ============================================================================
-- Database Schema for Recipe Management and Meal Planner
-- Author: Samar Khajuria
-- Student ID: T00714740
-- Course: COMP 3541 - Thompson Rivers University
-- ============================================================================

-- Create database and select it
CREATE DATABASE IF NOT EXISTS recipe_planner;
USE recipe_planner;

-- ============================================================================
-- Drop existing tables (for clean reinstall)
-- ============================================================================
DROP TABLE IF EXISTS meal_plans;
DROP TABLE IF EXISTS recipes;
DROP TABLE IF EXISTS users;

-- ============================================================================
-- Users Table
-- Stores registered user accounts
-- ============================================================================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- Recipes Table
-- Stores all recipes created by users
-- ============================================================================
CREATE TABLE recipes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  ingredients TEXT NOT NULL,
  instructions TEXT NOT NULL,
  prep_time INT DEFAULT 0 COMMENT 'Preparation time in minutes',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- Meal Plans Table
-- Links recipes to specific dates and meal types
-- ============================================================================
CREATE TABLE meal_plans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  recipe_id INT NOT NULL,
  meal_date DATE NOT NULL,
  meal_type ENUM('breakfast', 'lunch', 'dinner', 'snack') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- Indexes for better query performance
-- ============================================================================
CREATE INDEX idx_recipes_user ON recipes(user_id);
CREATE INDEX idx_recipes_title ON recipes(title);
CREATE INDEX idx_meal_plans_user ON meal_plans(user_id);
CREATE INDEX idx_meal_plans_date ON meal_plans(meal_date);

-- ============================================================================
-- Sample Data for Testing
-- ============================================================================

-- Test User (password: password)
INSERT INTO users (email, password_hash, name) VALUES 
('test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test User'),
('samar@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Samar Khajuria');

-- ============================================================================
-- Indian Recipes for Samar (user_id = 2)
-- ============================================================================

INSERT INTO recipes (user_id, title, description, ingredients, instructions, prep_time) VALUES

-- Recipe 1: Butter Chicken
(2, 'Butter Chicken (Murgh Makhani)', 'Creamy, rich tomato-based chicken curry - a North Indian classic.',
'500g boneless chicken, cubed
1 cup yogurt
2 tbsp lemon juice
1 tsp turmeric powder
2 tsp garam masala
2 tsp red chili powder
1 large onion, finely chopped
4 cloves garlic, minced
1 inch ginger, grated
400g tomato puree
1 cup heavy cream
4 tbsp butter
2 tbsp oil
1 tsp kasuri methi (dried fenugreek leaves)
Salt to taste
Fresh coriander for garnish',
'1. Marinate chicken with yogurt, lemon juice, turmeric, 1 tsp garam masala, and chili powder for 2 hours.
2. Grill or pan-fry marinated chicken until cooked. Set aside.
3. In a pan, heat butter and oil. Sauté onions until golden.
4. Add garlic and ginger, cook for 1 minute.
5. Add tomato puree and cook for 10-15 minutes until oil separates.
6. Add remaining garam masala and kasuri methi.
7. Pour in cream and stir well.
8. Add grilled chicken and simmer for 10 minutes.
9. Garnish with fresh coriander and serve with naan or rice.',
45),

-- Recipe 2: Paneer Tikka
(2, 'Paneer Tikka', 'Smoky grilled cottage cheese cubes marinated in spiced yogurt.',
'400g paneer, cubed
1 cup thick yogurt
1 tbsp ginger-garlic paste
1 tsp turmeric powder
2 tsp red chili powder
1 tsp cumin powder
1 tsp coriander powder
1 tsp garam masala
2 tbsp mustard oil
1 bell pepper, cubed
1 onion, cubed
Salt to taste
Chaat masala for garnish
Lemon wedges',
'1. Mix yogurt with all spices, ginger-garlic paste, mustard oil, and salt.
2. Add paneer cubes, bell pepper, and onion. Marinate for 1-2 hours.
3. Thread onto skewers alternating paneer and vegetables.
4. Grill in oven at 200°C or on tandoor for 15-20 minutes, turning once.
5. Sprinkle with chaat masala and serve with mint chutney and lemon wedges.',
30),

-- Recipe 3: Dal Makhani
(2, 'Dal Makhani', 'Creamy black lentils slow-cooked with butter and cream - a Punjabi specialty.',
'1 cup whole black urad dal
1/4 cup rajma (kidney beans)
4 tbsp butter
1 large onion, finely chopped
2 tomatoes, pureed
1 tbsp ginger-garlic paste
2 tsp red chili powder
1 tsp cumin powder
1/2 cup cream
Salt to taste
Fresh coriander
1 tsp kasuri methi',
'1. Soak dal and rajma overnight. Pressure cook until soft (about 30 minutes).
2. In a pan, heat butter. Sauté onions until golden brown.
3. Add ginger-garlic paste and cook for 1 minute.
4. Add tomato puree and cook until oil separates.
5. Add chili powder, cumin powder, and salt.
6. Add cooked dal and mash slightly. Add water if needed.
7. Simmer on low heat for 30-45 minutes, stirring occasionally.
8. Add cream and kasuri methi. Cook for 5 more minutes.
9. Top with butter and coriander. Serve with naan.',
60),

-- Recipe 4: Chicken Tikka Masala
(2, 'Chicken Tikka Masala', 'Grilled chicken pieces in a spiced creamy tomato sauce.',
'500g boneless chicken, cubed
1 cup yogurt
1 tbsp lemon juice
2 tsp garam masala
1 tsp cumin powder
1 tsp coriander powder
2 tsp paprika
1 large onion, chopped
3 cloves garlic, minced
1 inch ginger
400g tomato puree
1/2 cup cream
3 tbsp oil
Salt to taste
Fresh coriander',
'1. Marinate chicken in yogurt, lemon juice, half the spices for 2 hours.
2. Grill chicken until charred and cooked through.
3. Sauté onions until golden, add garlic and ginger.
4. Add tomato puree, remaining spices. Cook 15 minutes.
5. Add cream and grilled chicken. Simmer 10 minutes.
6. Garnish with coriander. Serve with rice or naan.',
50),

-- Recipe 5: Chole (Chickpea Curry)
(2, 'Chole (Punjabi Chickpea Curry)', 'Spiced chickpeas in a tangy tomato-onion gravy.',
'2 cups chickpeas, soaked overnight
2 onions, finely chopped
2 tomatoes, pureed
1 tbsp ginger-garlic paste
2 tsp chole masala
1 tsp cumin seeds
1 tsp turmeric
1 tsp red chili powder
1 tea bag (for color)
3 tbsp oil
Salt to taste
Fresh coriander
Ginger julienne',
'1. Boil chickpeas with tea bag until soft. Discard tea bag.
2. Heat oil, add cumin seeds. Add onions and sauté until brown.
3. Add ginger-garlic paste, cook 1 minute.
4. Add tomato puree, turmeric, chili powder. Cook until oil separates.
5. Add chickpeas with some cooking water.
6. Add chole masala and salt. Simmer 20 minutes.
7. Garnish with coriander and ginger. Serve with bhature or rice.',
40),

-- Recipe 6: Palak Paneer
(2, 'Palak Paneer', 'Cottage cheese cubes in a creamy spinach gravy.',
'250g paneer, cubed
500g spinach leaves
1 onion, chopped
2 tomatoes, chopped
1 tbsp ginger-garlic paste
2 green chilies
1/2 cup cream
1 tsp cumin seeds
1 tsp garam masala
3 tbsp butter
Salt to taste',
'1. Blanch spinach in boiling water for 2 minutes. Transfer to ice water.
2. Blend spinach with green chilies to a smooth paste.
3. Heat butter, add cumin seeds. Sauté onions until soft.
4. Add ginger-garlic paste and tomatoes. Cook until soft.
5. Add spinach puree, garam masala, and salt. Cook 5 minutes.
6. Add paneer cubes and cream. Simmer 5 minutes.
7. Serve hot with roti or naan.',
35),

-- Recipe 7: Aloo Paratha
(2, 'Aloo Paratha', 'Stuffed potato flatbread - perfect Indian breakfast.',
'2 cups whole wheat flour
3 large potatoes, boiled and mashed
2 green chilies, finely chopped
1 inch ginger, grated
1/2 cup fresh coriander, chopped
1 tsp cumin powder
1 tsp red chili powder
1 tsp amchur (dry mango powder)
Salt to taste
Ghee for cooking',
'1. Knead flour with water and salt to make soft dough. Rest 20 minutes.
2. Mix mashed potatoes with chilies, ginger, coriander, and all spices.
3. Divide dough into balls. Roll out, place filling, seal, and roll again.
4. Cook on hot tawa with ghee until golden on both sides.
5. Serve hot with yogurt, pickle, and butter.',
30),

-- Recipe 8: Rajma (Kidney Bean Curry)
(2, 'Rajma Chawal', 'Comforting kidney bean curry - North Indian soul food.',
'2 cups rajma (kidney beans), soaked overnight
2 onions, finely chopped
2 tomatoes, pureed
1 tbsp ginger-garlic paste
1 tsp cumin seeds
1 tsp turmeric
2 tsp red chili powder
1 tsp garam masala
3 tbsp oil
Salt to taste
Fresh coriander',
'1. Pressure cook rajma until completely soft (about 30-40 minutes).
2. Heat oil, add cumin seeds. Sauté onions until golden.
3. Add ginger-garlic paste, cook 1 minute.
4. Add tomato puree, turmeric, chili powder. Cook 10 minutes.
5. Add rajma with cooking liquid. Add salt and simmer 15 minutes.
6. Add garam masala. Mash some beans for thickness.
7. Garnish with coriander. Serve over steamed basmati rice.',
45),

-- Recipe 9: Masala Dosa
(2, 'Masala Dosa', 'Crispy South Indian crepe with spiced potato filling.',
'For Batter:
2 cups rice
1 cup urad dal
1/2 tsp fenugreek seeds
Salt to taste

For Filling:
4 potatoes, boiled and mashed
1 onion, sliced
1 tsp mustard seeds
10 curry leaves
2 green chilies
1/2 tsp turmeric
Salt to taste
Oil for cooking',
'1. Soak rice, dal, and fenugreek separately for 6 hours. Grind to smooth batter.
2. Ferment batter overnight in warm place.
3. For filling: Heat oil, add mustard seeds and curry leaves.
4. Add onions and green chilies. Sauté until soft.
5. Add turmeric and mashed potatoes. Mix well.
6. Spread thin layer of batter on hot tawa. Drizzle oil.
7. When crispy, place potato filling and fold.
8. Serve with sambar and coconut chutney.',
30),

-- Recipe 10: Samosa
(2, 'Samosa', 'Crispy fried pastry with spiced potato filling.',
'For Dough:
2 cups maida (all-purpose flour)
4 tbsp ghee
Salt to taste
Water as needed

For Filling:
4 potatoes, boiled and cubed
1 cup green peas
1 tsp cumin seeds
1 inch ginger, grated
2 green chilies
1 tsp coriander powder
1/2 tsp garam masala
Oil for deep frying',
'1. Make stiff dough with flour, ghee, salt, and water. Rest 30 minutes.
2. For filling: Heat oil, add cumin seeds.
3. Add peas, ginger, chilies. Cook 2 minutes.
4. Add potatoes and all spices. Mix well and cool.
5. Divide dough into balls. Roll into ovals, cut in half.
6. Form cone, fill with potato mixture, seal edges.
7. Deep fry until golden brown.
8. Serve hot with tamarind and mint chutney.',
45),

-- Recipe 11: Biryani
(2, 'Chicken Biryani', 'Aromatic layered rice dish with spiced chicken - Hyderabadi style.',
'500g chicken pieces
2 cups basmati rice, soaked
2 onions, sliced thin
1 cup yogurt
1 tbsp ginger-garlic paste
1 tsp turmeric
2 tsp biryani masala
1/2 tsp red chili powder
1/2 cup warm milk
Few strands saffron
Fresh mint and coriander
4 tbsp ghee
Whole spices (bay leaf, cardamom, cloves, cinnamon)
Salt to taste',
'1. Fry onions until deep golden brown. Set half aside for garnish.
2. Marinate chicken with yogurt, ginger-garlic paste, turmeric, chili powder and half the fried onions for 1 hour.
3. Parboil rice with whole spices until 70% cooked. Drain.
4. In heavy pot, layer marinated chicken at bottom.
5. Cover with rice. Top with remaining fried onions, mint, coriander.
6. Add saffron milk, dot with ghee.
7. Seal with foil and lid. Cook on low heat for 30-40 minutes.
8. Gently mix before serving. Serve with raita.',
60),

-- Recipe 12: Poori with Aloo
(2, 'Poori Aloo', 'Fluffy fried bread with spiced potato curry.',
'For Poori:
2 cups whole wheat flour
1 tbsp oil
Salt to taste
Water as needed
Oil for deep frying

For Aloo:
4 potatoes, boiled and cubed
1 tsp mustard seeds
1 tsp cumin seeds
1/4 tsp asafoetida
1/2 tsp turmeric
1 tsp red chili powder
2 green chilies
Fresh coriander',
'1. Knead flour with oil, salt, and water into stiff dough. Rest 15 minutes.
2. For aloo: Heat oil, add mustard seeds, cumin, and asafoetida.
3. Add green chilies, turmeric, and chili powder.
4. Add potatoes, salt, and little water. Cook 5 minutes.
5. Roll dough into small circles. Deep fry until puffed and golden.
6. Serve hot pooris with aloo curry.',
25);

-- ============================================================================
-- Basic Recipes for Test User (user_id = 1)
-- ============================================================================

INSERT INTO recipes (user_id, title, description, ingredients, instructions, prep_time) VALUES
(1, 'Classic Pancakes', 'Fluffy homemade pancakes perfect for breakfast.', 
'2 cups all-purpose flour
2 tablespoons sugar
2 teaspoons baking powder
1 teaspoon salt
2 eggs
1 3/4 cups milk
1/4 cup melted butter
1 teaspoon vanilla extract',
'1. Mix dry ingredients in a large bowl.
2. Whisk eggs, milk, butter, and vanilla in another bowl.
3. Pour wet ingredients into dry and stir until just combined.
4. Heat a griddle over medium heat and lightly grease.
5. Pour 1/4 cup batter per pancake.
6. Cook until bubbles form, then flip and cook until golden.',
15),

(1, 'Grilled Cheese Sandwich', 'A simple and delicious comfort food classic.',
'2 slices bread
2 slices cheddar cheese
1 tablespoon butter',
'1. Butter one side of each bread slice.
2. Place one slice butter-side down in a pan.
3. Add cheese slices on top.
4. Cover with second bread slice, butter-side up.
5. Cook on medium heat until golden, about 3 minutes per side.',
10);

-- ============================================================================
-- Meal Plans for Samar (user_id = 2) - Next 2 weeks starting Jan 31, 2026
-- ============================================================================

INSERT INTO meal_plans (user_id, recipe_id, meal_date, meal_type) VALUES
-- Jan 31 (Today - Friday)
(2, 7, '2026-01-31', 'breakfast'),   -- Aloo Paratha
(2, 5, '2026-01-31', 'lunch'),       -- Chole
(2, 1, '2026-01-31', 'dinner'),      -- Butter Chicken

-- Feb 1 (Saturday)
(2, 9, '2026-02-01', 'breakfast'),   -- Masala Dosa
(2, 8, '2026-02-01', 'lunch'),       -- Rajma Chawal
(2, 4, '2026-02-01', 'dinner'),      -- Chicken Tikka Masala

-- Feb 2 (Sunday)
(2, 12, '2026-02-02', 'breakfast'),  -- Poori Aloo
(2, 11, '2026-02-02', 'lunch'),      -- Biryani
(2, 6, '2026-02-02', 'dinner'),      -- Palak Paneer

-- Feb 3 (Monday)
(2, 7, '2026-02-03', 'breakfast'),   -- Aloo Paratha
(2, 3, '2026-02-03', 'lunch'),       -- Dal Makhani
(2, 2, '2026-02-03', 'dinner'),      -- Paneer Tikka
(2, 10, '2026-02-03', 'snack'),      -- Samosa

-- Feb 4 (Tuesday)
(2, 9, '2026-02-04', 'breakfast'),   -- Masala Dosa
(2, 5, '2026-02-04', 'lunch'),       -- Chole
(2, 1, '2026-02-04', 'dinner'),      -- Butter Chicken

-- Feb 5 (Wednesday)
(2, 7, '2026-02-05', 'breakfast'),   -- Aloo Paratha
(2, 6, '2026-02-05', 'lunch'),       -- Palak Paneer
(2, 11, '2026-02-05', 'dinner'),     -- Biryani

-- Feb 6 (Thursday)
(2, 12, '2026-02-06', 'breakfast'),  -- Poori Aloo
(2, 8, '2026-02-06', 'lunch'),       -- Rajma
(2, 4, '2026-02-06', 'dinner'),      -- Chicken Tikka Masala
(2, 10, '2026-02-06', 'snack'),      -- Samosa

-- Feb 7 (Friday)
(2, 9, '2026-02-07', 'breakfast'),   -- Masala Dosa
(2, 3, '2026-02-07', 'lunch'),       -- Dal Makhani
(2, 1, '2026-02-07', 'dinner'),      -- Butter Chicken

-- Feb 8 (Saturday)
(2, 7, '2026-02-08', 'breakfast'),   -- Aloo Paratha
(2, 5, '2026-02-08', 'lunch'),       -- Chole
(2, 2, '2026-02-08', 'dinner'),      -- Paneer Tikka

-- Feb 9 (Sunday)
(2, 12, '2026-02-09', 'breakfast'),  -- Poori Aloo
(2, 11, '2026-02-09', 'lunch'),      -- Biryani
(2, 1, '2026-02-09', 'dinner'),      -- Butter Chicken

-- Feb 10 (Monday)
(2, 9, '2026-02-10', 'breakfast'),   -- Masala Dosa
(2, 8, '2026-02-10', 'lunch'),       -- Rajma
(2, 6, '2026-02-10', 'dinner'),      -- Palak Paneer

-- Feb 11 (Tuesday)
(2, 7, '2026-02-11', 'breakfast'),   -- Aloo Paratha
(2, 3, '2026-02-11', 'lunch'),       -- Dal Makhani
(2, 4, '2026-02-11', 'dinner'),      -- Chicken Tikka Masala

-- Feb 12 (Wednesday)
(2, 12, '2026-02-12', 'breakfast'),  -- Poori Aloo
(2, 5, '2026-02-12', 'lunch'),       -- Chole
(2, 11, '2026-02-12', 'dinner'),     -- Biryani
(2, 10, '2026-02-12', 'snack'),      -- Samosa

-- Feb 13 (Thursday)
(2, 9, '2026-02-13', 'breakfast'),   -- Masala Dosa
(2, 6, '2026-02-13', 'lunch'),       -- Palak Paneer
(2, 1, '2026-02-13', 'dinner');      -- Butter Chicken

-- Sample Meal Plans for Test User (user_id = 1)
INSERT INTO meal_plans (user_id, recipe_id, meal_date, meal_type) VALUES
(1, 13, '2026-01-31', 'breakfast'),  -- Pancakes
(1, 14, '2026-01-31', 'lunch'),      -- Grilled Cheese
(1, 13, '2026-02-01', 'breakfast');  -- Pancakes

-- ============================================================================
-- End of Schema
-- ============================================================================
