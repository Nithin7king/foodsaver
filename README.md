# foodsaver
# 🍱 FoodSaver – A Platform to Reduce Food Waste

## 🌍 Overview
**FoodSaver** is a web-based platform designed to minimize food waste by connecting food providers with consumers, NGOs, and biogas plants. It allows hotels, restaurants, and food businesses to sell, donate, or recycle near-expiry food efficiently.

## 🚀 Features
### 🏨 For Food Providers (Hotels/Restaurants)
- Add dishes with expiry details and prices.  
- Edit or remove listings anytime.  
- View current and past food postings.

### 👥 For Buyers
- Purchase **near-expiry food at discounted prices**.  
- Search and filter dishes by name, price, or expiry time.

### 💖 For NGOs / Charities
- Accept **donations** of near-expiry but safe-to-eat food.  
- Connect with food providers for direct delivery.

### 🔋 For Biogas Plants
- Buy **expired vegetables and organic waste** to convert into bioenergy.

## 🧩 Modules
| Module | Description |
|--------|--------------|
| `food.php` | Displays all available dishes with search options. |
| `edit_food.php` | Allows logged-in companies to add, update, or delete dishes. |
| `register.php` / `login.php` | Handles company registration and authentication. |
| `donation.php` | Manages donation requests to NGOs. |
| `database/food.sql` | MySQL schema for storing food, company, and transaction data. |

## ⚙️ Tech Stack
- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL  
- **Hosting:** XAMPP / Apache  

## 🧠 How It Works
1. Hotels and restaurants register and add food details (name, expiry date, price).  
2. Users browse and purchase discounted near-expiry food.  
3. Food nearing expiry can be automatically offered to orphanages or NGOs.  
4. Expired items (like vegetables) are redirected to biogas plants.  

## 📸 Screenshots (Optional)
_Add screenshots of your pages like `food.php`, `edit_food.php`, and dashboard here._

## 🏗️ Setup Instructions
1. Clone this repository:
   ```bash
   git clone https://github.com/<your-username>/foodsaver.git







