-- Update products table to support best sellers while preserving original categories
-- Add is_best_seller column to products table
ALTER TABLE products ADD COLUMN is_best_seller TINYINT(1) DEFAULT 0;

-- Remove 'best_sellers' from category enum and update existing best_sellers products
-- First, let's see what products are currently marked as best_sellers
-- UPDATE products SET is_best_seller = 1 WHERE category = 'best_sellers';

-- For now, let's mark some sample products as best sellers for demonstration
-- You can change these based on your preference
UPDATE products SET is_best_seller = 1 WHERE product_id IN (1, 6, 11); -- Example: men's jeans, women's dress, kids' shirt

-- Update the category enum to remove best_sellers (optional)
-- ALTER TABLE products MODIFY COLUMN category ENUM('mens','womens','kids') NOT NULL;