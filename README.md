# Mensahe.ai

A next-generation instant messaging platform with secure peer-to-peer communication and a modern web interface.

## 🚀 Quick Start

### Prerequisites
- PHP 8.0+ with Composer
- Node.js 16+ (for extension development)
- Modern web browser with WebAuthn support

### Backend Setup
```bash
# Install PHP dependencies
cd src/server
composer install

# Start the development server
./start-server.sh
# Or from project root:
# ./start-server.sh
```

The backend will be available at `http://localhost:8080`

### Frontend Testing
Open `http://localhost:8080/test-frontend.html` in your browser to test the messaging interface.

### Extension Development
```bash
# Install Node.js dependencies
npm install

# Build the extension
npm run build:extension

# Load extension from extension-dist/ in Chrome
```

## 🌐 Landing Page

A marketing-focused landing page is available in the `static-site/` folder, designed for GitHub Pages deployment with nostalgic 90s-2000s messaging vibes.

### Local Development
```bash
# Navigate to static site
cd static-site

# Open in browser
open index.html

# Or serve with local server
python3 -m http.server 8000
# Visit http://localhost:8000
```

### Deployment
```bash
# Use the deployment script
cd static-site
./deploy.sh

# Or manually deploy to GitHub Pages
# See static-site/README.md for detailed instructions
```

The landing page includes:
- **Marketing copy** focused on instant messaging and social connections
- **Waitlist collection** for early access signups
- **Responsive design** matching the app's UI
- **SEO optimization** with proper meta tags

## 🏗️ Architecture

### Backend (PHP)
- **Environment-driven configuration** - All settings via environment variables
- **WebAuthn integration** - Secure passkey registration and verification
- **RESTful API** - JSON-based communication
- **Comprehensive testing** - PHPUnit with 90%+ coverage

### Frontend (HTML/JavaScript)
- **Modern UI** - Clean, responsive passkey authentication interface
- **WebAuthn API** - Native browser passkey support
- **Email validation** - Client and server-side validation
- **Error handling** - User-friendly error messages

### Extension (Chrome)
- **Content script** - Injects passkey authentication into web pages
- **Background script** - Handles extension lifecycle
- **Popup interface** - User-friendly extension controls

## 🔧 Configuration

The backend uses environment variables for all configuration:

```bash
# App Configuration
APP_NAME=Mensahe
APP_ENV=development
APP_DEBUG=false
APP_URL=http://localhost:8080
APP_DOMAIN=localhost

# WebAuthn Configuration
WEBAUTHN_TIMEOUT=60000
WEBAUTHN_ATTESTATION=none
WEBAUTHN_USER_VERIFICATION=preferred
WEBAUTHN_AUTHENTICATOR_ATTACHMENT=platform

# CORS Configuration
CORS_ALLOWED_ORIGINS=*
CORS_ALLOWED_METHODS=GET,POST,OPTIONS
CORS_ALLOWED_HEADERS=Content-Type
```

## 🧪 Testing

For comprehensive testing documentation, see [TESTING.md](TESTING.md).

### Quick Test Run

```bash
# Backend tests
cd src/server
./run-tests.sh

# Frontend testing
./start-server.sh
# Open http://localhost:8080/test-frontend.html
```

## 📁 Project Structure

```
mensahe.ai/
├── static-site/           # Landing page for GitHub Pages
│   ├── index.html         # Main landing page
│   ├── README.md          # Deployment instructions
│   └── deploy.sh          # Deployment script
├── src/
│   ├── extension/          # Chrome extension
│   │   ├── assets/         # Icons and logos
│   │   ├── background.js   # Extension background script
│   │   ├── content.js      # Content script for web pages
│   │   ├── popup.html      # Extension popup interface
│   │   └── manifest.json   # Extension manifest
│   └── server/             # PHP backend
│       ├── lib/            # Core library classes
│       ├── tests/          # PHPUnit tests
│       ├── registerRequest.php    # Registration endpoint
│       ├── registerVerify.php     # Verification endpoint
│       └── test-frontend.html     # Test interface
├── start-server.sh         # Convenience script to start backend
└── README.md              # This file
```

## 🔐 Security Features

- **WebAuthn standard** - Industry-standard passkey authentication
- **Environment configuration** - No hardcoded secrets
- **Input validation** - Server-side email validation
- **Session management** - Secure challenge storage
- **CORS protection** - Configurable cross-origin policies

## 🚀 Development

### Adding New Features
1. Create feature branch
2. Add tests for new functionality
3. Implement the feature
4. Ensure all tests pass
5. Update documentation
6. Submit pull request

### Code Quality
- All backend code must have unit tests
- Follow PSR-12 coding standards
- Use environment variables for configuration
- Document API endpoints

## 📚 Documentation

- [Backend Testing Guide](src/server/TESTING.md)
- [Extension Development](src/extension/README.md)
- [API Documentation](src/server/README.md)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.
