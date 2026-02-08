# Real-Time Weather Dashboard 🌤️

A dynamic weather dashboard that fetches real-time weather data from RapidAPI and displays it in a clean, user-friendly interface. Built for the Postman API Hackathon.

![Weather Dashboard](https://img.shields.io/badge/Status-Active-success)
![PHP](https://img.shields.io/badge/PHP-7.4+-blue)
![License](https://img.shields.io/badge/License-MIT-green)

## ✨ Features

- 🌍 **Real-Time Weather Data** - Fetches live weather information from RapidAPI WeatherAPI
- 🎨 **Clean UI** - Modern, responsive design with weather-specific icons
- 💾 **Smart Caching** - Stores weather data in MySQL database with 1-hour cache
- ⚡ **Fast Response** - Returns cached data for recent searches to improve performance
- 🔍 **Easy Search** - Search by city name with Enter key support
- 📱 **Responsive Design** - Works seamlessly on desktop and mobile devices
- 🛡️ **Error Handling** - Comprehensive error messages for better user experience

## 🖼️ Screenshots

![Weather Dashboard Interface](./screenshots/dashboard.png)

## 🚀 Technologies Used

### Frontend
- **HTML5** - Structure and semantic markup
- **CSS3** - Styling with modern design patterns
- **JavaScript (Vanilla)** - Dynamic UI updates and API calls

### Backend
- **PHP 7.4+** - Server-side logic and API integration
- **MySQL** - Database for storing weather search history

### API
- **RapidAPI WeatherAPI** - Real-time weather data provider

### Tools
- **Postman** - API testing and development
- **Git** - Version control

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- PHP 7.4 or higher
- MySQL 5.7 or higher
- A web server (Apache/Nginx) or PHP built-in server
- RapidAPI account with WeatherAPI access

## 🔧 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/Ishikakaur8072005/real-time-weather-dashboard.git
cd real-time-weather-dashboard
```

### 2. Database Setup

Create the database and table using the provided SQL script:

```bash
mysql -u root -p < Final/fwd/database.sql
```

Or manually create the database:

```sql
CREATE DATABASE IF NOT EXISTS POSTMAN;
USE POSTMAN;

CREATE TABLE IF NOT EXISTS weather_searches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city_name VARCHAR(100) NOT NULL,
    temperature DECIMAL(5,2) NOT NULL,
    CONDITIONs VARCHAR(100) NOT NULL,
    rain_chance INT NOT NULL,
    wind_speed DECIMAL(5,2) NOT NULL,
    humidity INT NOT NULL,
    visibility DECIMAL(5,2) NOT NULL,
    pressure DECIMAL(6,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_city_name (city_name),
    INDEX idx_created_at (created_at)
);
```

### 3. Configure Environment Variables

Copy the example environment file and add your API credentials:

```bash
cd Final/fwd
cp .env.example .env
```

Edit the `.env` file with your credentials:

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=POSTMAN
DB_USER=root
DB_PASS=your_database_password

# RapidAPI Weather Configuration
RAPIDAPI_KEY=your_rapidapi_key_here
RAPIDAPI_HOST=weatherapi-com.p.rapidapi.com
```

### 4. Get RapidAPI Key

1. Sign up at [RapidAPI](https://rapidapi.com/)
2. Subscribe to [WeatherAPI](https://rapidapi.com/weatherapi/api/weatherapi-com/)
3. Copy your API key from the dashboard
4. Paste it in the `.env` file

### 5. Start the Application

Using PHP built-in server:

```bash
cd Final/fwd
php -S localhost:8000
```

Or configure your web server to point to the `Final/fwd` directory.

### 6. Access the Application

Open your browser and navigate to:
```
http://localhost:8000/Final.html
```

## 📖 Usage

1. **Search for Weather**: Enter a city name in the search box
2. **Press Enter or Click Search**: The app will fetch current weather data
3. **View Results**: See temperature, conditions, humidity, wind speed, visibility, and pressure
4. **Cached Results**: Recent searches (within 1 hour) return instantly from cache

## 🏗️ Project Structure

```
real-time-weather-dashboard/
├── Final/
│   └── fwd/
│       ├── Final.html          # Main application interface
│       ├── insert_weather.php  # Backend API handler
│       ├── config.php          # Configuration loader
│       ├── database.sql        # Database schema
│       ├── .env.example        # Environment variables template
│       └── download.png        # App icon/logo
├── README.md                   # Project documentation
└── .gitignore                  # Git ignore rules
```

## 🔐 Security Notes

- Never commit your `.env` file to version control
- The `.env.example` file is provided as a template only
- Keep your RapidAPI key secure and don't share it publicly
- Use environment variables for sensitive configuration

## 🐛 Troubleshooting

### "Database connection failed"
- Verify MySQL is running
- Check database credentials in `.env` file
- Ensure the POSTMAN database exists

### "Weather data not found for this city"
- Check your RapidAPI key is valid
- Verify you have an active subscription to WeatherAPI
- Try a different city name (use common English names)

### Mock Data Appears
- This happens when no API key is configured
- Add your RapidAPI key to the `.env` file
- The app falls back to random data for testing purposes

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👤 Author

**Ishika Kaur**
- GitHub: [@Ishikakaur8072005](https://github.com/Ishikakaur8072005)

## 🙏 Acknowledgments

- [RapidAPI](https://rapidapi.com/) for providing the weather API
- [WeatherAPI](https://www.weatherapi.com/) for reliable weather data
- [Postman](https://www.postman.com/) for API testing tools
- Built for the Postman API Hackathon

## 📞 Support

If you have any questions or run into issues, please open an issue on GitHub.

---

**Made with ❤️ for the Postman API Hackathon**
