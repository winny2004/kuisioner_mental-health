# Sistem Kuisioner Kesehatan Mental - Laravel

## Overview

Sistem kuisioner kesehatan mental berbasis web yang terintegrasi dengan AI untuk prediksi kondisi mental (Depression, Anxiety, Stress) menggunakan algoritma Random Forest.

## Tech Stack

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL
- **Frontend**: Blade Templates, TailwindCSS
- **AI Backend**: Flask API (Python)
- **Machine Learning**: Random Forest Classifier

## Features

- ✅ User Authentication (Login/Register)
- ✅ Multiple Quiz Types:
  - Family Social (MSPSS + DASS-21)
  - Self Efficacy
- ✅ AI-Powered Prediction (Normal/Depresi/Cemas/Stres)
- ✅ Severity Level Analysis (Ringan/Sedang/Berat/Sangat Berat)
- ✅ Quiz History
- ✅ Real-time Prediction Results
- ✅ Responsive Design

## Project Structure

```
kuisioner-mentalhealth/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php       # Authentication logic
│   │       ├── HomeController.php       # Dashboard logic
│   │       └── QuizController.php       # Quiz & AI integration
│   ├── Models/
│   │   ├── User.php                    # User model
│   │   ├── Question.php                # Question model
│   │   ├── Answer.php                  # Answer model
│   │   └── QuizResult.php              # Quiz result model
│   └── Services/
│       └── FlaskApiService.php         # Flask API communication
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php           # Main layout
│       ├── auth/
│       │   ├── login.blade.php         # Login page
│       │   └── register.blade.php      # Register page
│       ├── quiz/
│       │   ├── index.blade.php         # Quiz selection
│       │   ├── start.blade.php         # Quiz questions
│       │   ├── result.blade.php        # AI prediction results
│       │   └── history.blade.php       # Quiz history
│       ├── home.blade.php              # Dashboard
│       └── landing.blade.php           # Landing page
├── database/
│   ├── migrations/                     # Database migrations
│   └── seeders/                        # Database seeders
├── routes/
│   └── web.php                         # Web routes
├── .env                                # Environment configuration
├── composer.json                       # PHP dependencies
└── README.md                           # This file
```

## Installation

### Prerequisites

1. PHP 8.2 or higher
2. Composer
3. MySQL 5.7+ or MariaDB 10.3+
4. Node.js & NPM (for assets)

### Step 1: Clone Repository

```bash
cd D:\Skripsi\project\kuisioner-mentalhealth
```

### Step 2: Install Dependencies

```bash
composer install
npm install
```

### Step 3: Environment Setup

1. **Copy environment file**:
   ```bash
   cp .env.example .env
   ```

2. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

3. **Configure .env file**:
   ```env
   APP_NAME="Kuisioner Mental Health"
   APP_ENV=local
   APP_KEY=base64:...
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kuisioner_mentalhealth
   DB_USERNAME=root
   DB_PASSWORD=

   # Flask API Configuration
   FLASK_API_URL=http://127.0.0.1:5000
   ```

### Step 4: Database Setup

1. **Create database**:
   ```sql
   CREATE DATABASE kuisioner_mentalhealth;
   ```

2. **Run migrations**:
   ```bash
   php artisan migrate
   ```

3. **Run seeders** (optional):
   ```bash
   php artisan db:seed
   ```

### Step 5: Build Assets

```bash
npm run build
```

### Step 6: Start Development Server

```bash
php artisan serve
```

Application will be available at `http://localhost:8000`

## Configuration

### Flask API Integration

The system integrates with Flask API for AI predictions. Configure in:

**1. `.env` file**:
```env
FLASK_API_URL=http://127.0.0.1:5000
```

**2. `config/services.php`**:
```php
'flask' => [
    'url' => env('FLASK_API_URL', 'http://127.0.0.1:5000'),
],
```

### Starting Flask API

Before using the application, start the Flask API:

```bash
cd D:\Skripsi\project\backend
python app.py
```

Flask will run on `http://127.0.0.1:5000`

## Database Schema

### Users Table
```sql
- id
- name
- email
- password
- created_at
- updated_at
```

### Questions Table
```sql
- id
- type (family_social, self_efficacy)
- scale_type (likert_5, likert_4, likert_7, dass21)
- question_text
- order
- is_active
- created_at
- updated_at
```

### Answers Table
```sql
- id
- user_id
- question_id
- score
- updated_at
```

### Quiz Results Table
```sql
- id
- user_id
- quiz_type (family_social, self_efficacy)
- total_score
- max_score
- category (Normal, Depresi, Cemas, Stres, tinggi, sedang, rendah)
- feedback
- prediction_data (JSON) ← AI prediction results
- completed_at
```

## Routes

### Authentication Routes
```php
GET  /login          - Login page
POST /login          - Login submit
GET  /register       - Register page
POST /register       - Register submit
POST /logout         - Logout
```

### Home Routes
```php
GET /home            - User dashboard (auth required)
```

### Quiz Routes
```php
GET  /quiz                    - Quiz selection page
GET  /quiz/start/{type}       - Start quiz (family_social, self_efficacy)
POST /quiz/submit/{type}      - Submit quiz answers
GET  /quiz/result/{type}      - View quiz results
GET  /quiz/history            - Quiz history
```

## Usage Guide

### For Users

#### 1. Register & Login

1. Go to `http://localhost:8000`
2. Click "Daftar" to register
3. Fill in name, email, and password
4. Click "Masuk" to login

#### 2. Select Quiz

1. After login, you'll see quiz options
2. Choose:
   - **Family Social**: MSPSS + DASS-21 (with AI prediction)
   - **Self Efficacy**: Self-efficacy + Well-being

#### 3. Complete Quiz

1. Read each question carefully
2. Select the most appropriate answer
3. Click "Selesaikan Kuisioner" to submit

#### 4. View Results

**For Family Social Quiz**:

- **AI Prediction**: Normal/Depresi/Cemas/Stres
- **Emoji Indicator**: 😊/😔/😰/😓
- **Severity Categories**:
  - Depression (Depresi): 0-42
  - Anxiety (Cemas): 0-42
  - Stress (Stres): 0-42
- **Severity Levels**:
  - Normal (Hijau)
  - Mild/Ringan (Kuning)
  - Moderate/Sedang (Orange)
  - Severe/Berat (Merah)
  - Extremely Severe/Sangat Berat (Merah Tua)
- **MSPSS Scores**: Support system analysis
- **Total DASS**: X/126
- **Confidence Scores**: AI probability per category

**For Self Efficacy Quiz**:

- **Category**: Tinggi/Sedang/Rendah
- **Percentage Score**: X% dari total
- **Section Breakdown**: Self-efficacy & Well-being scores

#### 5. Quiz History

- View all completed quizzes
- See past results
- Compare progress over time

### For Developers

#### Adding New Questions

1. **Create migration**:
   ```bash
   php artisan make:migration add_new_questions
   ```

2. **Insert questions**:
   ```php
   DB::table('questions')->insert([
       'type' => 'family_social',
       'scale_type' => 'dass21',
       'question_text' => 'Pertanyaan baru...',
       'order' => 22,
       'is_active' => true
   ]);
   ```

3. **Run migration**:
   ```bash
   php artisan migrate
   ```

#### Modifying Quiz Logic

**QuizController.php**:
- `start()`: Load questions by type
- `submit()`: Process answers & integrate with Flask AI
- `result()`: Display results with AI prediction
- `history()`: Show user's quiz history

#### FlaskApiService

**Purpose**: Communicate with Flask API

```php
$flaskService = new FlaskApiService();
$quizData = $flaskService->transformQuizData($answers, $questions);
$predictionResult = $flaskService->predictMentalHealth($quizData);
```

**Methods**:
- `predictMentalHealth()`: Send data to Flask API
- `transformQuizData()`: Transform answers to Flask format
- `checkHealth()`: Check Flask API status

#### Customizing AI Feedback

**QuizController.php** - `generateAIFeedback()`:

```php
private function generateAIFeedback($predictionData) {
    $prediction = $predictionData['prediction'];
    
    // Customize feedback based on prediction
    switch ($prediction) {
        case 'Normal':
            return "Kondisi mental Anda normal...";
        case 'Depression':
            return "Terdeteksi indikasi depresi...";
        // ...
    }
}
```

## DASS-21 Scoring System

### Question Distribution

- **Depression**: DAS1-DAS7 (7 questions)
- **Anxiety**: DAS8-DAS14 (7 questions)
- **Stress**: DAS15-DAS21 (7 questions)

### Scoring

1. **Each question**: 0-3 points
2. **Raw score per category**: 0-21 (7 questions × 3)
3. **Final score** (×2): 0-42 per category
4. **Total**: 0-126 (42 + 42 + 42)

### Severity Levels

| Condition | Normal | Mild | Moderate | Severe | Extremely Severe |
|-----------|--------|------|----------|--------|------------------|
| **Depression** | 0-9 | 10-13 | 14-20 | 21-27 | 28+ |
| **Anxiety** | 0-7 | 8-9 | 10-14 | 15-19 | 20+ |
| **Stress** | 0-14 | 15-18 | 19-25 | 26-33 | 34+ |

## Troubleshooting

### Flask API Connection Error

**Symptoms**: Prediction fails with connection error

**Solutions**:
1. Ensure Flask API is running: `cd D:\Skripsi\project\backend && python app.py`
2. Check Flask API URL in `.env`: `FLASK_API_URL=http://127.0.0.1:5000`
3. Test Flask health: `curl http://127.0.0.1:5000/api/health`

### prediction_data is Null

**Symptoms**: Results don't show AI prediction

**Solutions**:
1. Check Laravel log: `tail -f storage/logs/laravel.log`
2. Verify QuizResult model has `prediction_data` in fillable
3. Check if Flask API returns valid response
4. Try submitting quiz again

### Category Still Shows tinggi/sedang/rendah

**Symptoms**: Old category system instead of AI prediction

**Solutions**:
1. Delete old quiz results
2. Submit quiz again
3. Check if Flask API is running
4. Verify `QuizController.php` uses AI prediction for family_social

### Database Migration Errors

**Symptoms**: Migration fails

**Solutions**:
1. Clear config cache: `php artisan config:clear`
2. Rollback migration: `php artisan migrate:rollback`
3. Check database connection in `.env`
4. Run migration again: `php artisan migrate`

### Asset Build Errors

**Symptoms**: CSS/JS not loading

**Solutions**:
1. Clear view cache: `php artisan view:clear`
2. Rebuild assets: `npm run build`
3. Check public folder permissions
4. Clear browser cache

## Development

### Code Style

- Follow PSR-12 coding standard
- Use Laravel conventions
- Add comments for complex logic
- Write unit tests for new features

### Testing

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter QuizTest
```

### Debugging

**Enable debug mode** in `.env`:
```env
APP_DEBUG=true
```

**Check logs**:
```bash
# Laravel log
tail -f storage/logs/laravel.log

# Flask log (in backend directory)
# Check console output
```

**Database query log**:
```php
DB::enableQueryLog();
// ... run queries
dd(DB::getQueryLog());
```

## Deployment

### Production Setup

1. **Environment**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Assets**:
   ```bash
   npm run build
   ```

4. **Permissions**:
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   ```

### Web Server Configuration

**Apache (.htaccess)**:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>
```

**Nginx**:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Security

### Best Practices

1. **Use HTTPS** in production
2. **Set strong password** for database
3. **Keep dependencies updated**:
   ```bash
   composer update
   npm update
   ```
4. **Use CSRF protection** (enabled by default)
5. **Validate user input**
6. **Sanitize output**
7. **Rate limiting** for API calls
8. **Regular backups**

### Environment Variables

**Never commit** `.env` file to version control

**Required variables**:
```env
APP_KEY=                    # Generate with php artisan key:generate
DB_PASSWORD=                # Strong password
FLASK_API_URL=             # Flask API endpoint
```

## Maintenance

### Regular Tasks

1. **Daily**: Monitor logs, check errors
2. **Weekly**: Backup database, check updates
3. **Monthly**: Update dependencies, review performance
4. **Quarterly**: Retrain ML model, audit security

### Backup Strategy

1. **Database backup**:
   ```bash
   mysqldump -u root -p kuisioner_mentalhealth > backup.sql
   ```

2. **Files backup**:
   - `.env`
   - `storage/app`
   - `database/migrations`

## API Documentation

### Flask API Endpoints

**Base URL**: `http://127.0.0.1:5000`

**Endpoints**:
- `GET /` - API info
- `GET /api/health` - Health check
- `POST /api/predict` - Predict mental health

See `D:\Skripsi\project\backend\README.md` for detailed API documentation.

## Contributing

For contributors:

1. Follow coding standards
2. Write tests for new features
3. Document changes
4. Submit pull request
5. Code review required

## License

Proprietary - Untuk keperluan Skripsi

## Support

### Issues & Bugs

Report issues:
1. Check existing issues first
2. Provide detailed description
3. Include error messages
4. Attach screenshots if applicable

### Feature Requests

1. Explain use case
2. Provide examples
3. Consider feasibility
4. Discuss with team

## Credits

- **Developer**: [Your Name]
- **Project**: Skripsi Sistem Prediksi Kesehatan Mental
- **Institution**: [Your University]
- **Year**: 2026

## References

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Templates](https://laravel.com/docs/blade)
- [TailwindCSS](https://tailwindcss.com/)
- [DASS-21 Scale](https://psychology-tools.com/depression-anxiety-stress-scales/)
- [MSPSS Scale](https://www.fostercare.ca/multidimensional-scale-perceived-social-support/)

## Changelog

### Version 1.0.0 (March 2026)
- Initial release
- User authentication
- Family Social quiz with AI prediction
- Self Efficacy quiz
- Quiz history
- AI-powered mental health prediction
- Severity level analysis
- Responsive design

## Roadmap

### Upcoming Features
- [ ] Admin dashboard
- [ ] Export results to PDF
- [ ] Email reports
- [ ] Dark mode
- [ ] Mobile app (React Native)
- [ ] Multi-language support
- [ ] Advanced analytics dashboard
- [ ] Integration with professional help services
- [ ] Anonymous quiz mode
- [ ] Comparison with population data

## Notes

### Important Notes

1. **DASS-21 Standard**: Skor dikalikan 2 (0-42 per kategori)
2. **AI Priority**: Family Social uses AI, Self Efficacy uses percentage
3. **Flask Dependency**: Must run Flask API before using Family Social quiz
4. **Database**: Run migrations after pulling updates
5. **Assets**: Rebuild after modifying frontend

### Known Limitations

1. **ML Model**: Trained on 450 samples - improve with more data
2. **Language**: Currently Indonesian only
3. **Accessibility**: Could improve WCAG compliance
4. **Mobile**: Responsive but not native mobile app

### Future Improvements

1. **More Training Data**: Improve model accuracy
2. **Additional Quiz Types**: Add more assessment tools
3. **Real-time Monitoring: Track mental health over time
4. **Integration**: Connect with healthcare providers
5. **Research**: Validate predictions with clinical diagnosis

---

**Last Updated**: March 11, 2026

**For questions or support**, please refer to the main project documentation or contact the development team.
