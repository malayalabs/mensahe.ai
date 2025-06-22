# mensahe.ai

<div align="center">
  <img src="src/extension/assets/mensahe-logo.svg" alt="Mensahe Logo" width="120" height="120">
  <h1>Mensahe Chat Widget</h1>
  <p><strong>A secure peer-to-peer messaging system with passkey authentication.</strong></p>
</div>

Mensahe.ai is a chat widget designed for secure, peer-to-peer communication, delivered as a lightweight and installable browser extension with modern passkey authentication.

## Features

- 🎨 **Modern UI**: A clean, modern interface with nostalgic themes
- 🔧 **Chrome Extension**: Install as a browser extension for easy access
- ⚡ **Fast & Lightweight**: Built with vanilla JavaScript for optimal performance
- 🔐 **Passkey Authentication**: Secure WebAuthn-based authentication system
- 🌐 **PHP Backend**: Robust server-side authentication and user management
- 🎭 **Nostalgic Themes**: Yahoo! Messenger and Windows XP inspired designs
- 📱 **Responsive Design**: Works seamlessly across different screen sizes

## Quick Start

### Prerequisites

- Node.js (for extension development)
- PHP 8.0+ (for backend server)
- Composer (for PHP dependencies)

### Backend Setup

For detailed backend setup and API documentation, see [src/server/README.md](src/server/README.md).

Quick setup:
```bash
cd src/server
composer install
./start-server.sh
```

### Extension Development

1. **Install dependencies:**
   ```bash
   npm install
   ```

2. **Build the extension for development:**
   ```bash
   npm run build:extension
   ```

### Installation

1. Open Chrome and navigate to `chrome://extensions/`

2. Enable "Developer mode" in the top right.

3. Click "Load unpacked" and select the `extension-dist` folder from this project.

4. The Mensahe icon will appear in your browser toolbar.

## Usage

### Authentication Flow

1. **Registration**: Users can register using passkeys (biometric, PIN, or security key)
2. **Login**: Secure authentication without passwords
3. **Session Management**: Automatic session handling

### Widget Usage

- Click the extension icon in the toolbar to open the popup
- Click "Toggle Widget on this Page" to show or hide the launcher icon
- Click the launcher icon on any webpage to open the chat widget
- Use passkey authentication to access the chat features
- Click outside the widget or use the close button to dismiss it

## Project Structure

```
mensahe.ai/
├── src/
│   ├── extension/            # Chrome extension source files
│   │   ├── assets/
│   │   │   ├── icons/        # PNG icons (16, 24, 32, 48, 64, 128, 256px)
│   │   │   └── mensahe-logo.svg # Main SVG logo
│   │   ├── manifest.json     # Extension manifest
│   │   ├── popup.html        # Extension popup UI
│   │   ├── popup.js          # Popup functionality
│   │   ├── popup.css         # Popup styles
│   │   ├── content.js        # Content script (injects widget)
│   │   ├── content.css       # Widget styles
│   │   └── background.js     # Background service worker
│   └── server/               # PHP backend
│       ├── README.md         # Backend documentation and API reference
│       ├── PassKeyAuthService.php # WebAuthn authentication service
│       ├── register-request.php   # Registration API endpoint
│       ├── composer.json          # PHP dependencies
│       └── ...                # Additional backend files
├── scripts/
│   └── generate-icons.mjs    # Icon generation script
├── vite.extension.config.js  # Extension build configuration
└── package.json              # Dependencies and scripts
```

## Development

### Backend Development

For comprehensive backend documentation, API reference, and development guides, see [src/server/README.md](src/server/README.md).

### Testing

1. **Backend Testing**: See [src/server/README.md](src/server/README.md) for detailed testing procedures
2. **Extension Testing**: Build and load the extension in Chrome to test the complete authentication flow

## Customization

### Widget Styling
- Widget styles: `src/extension/content.css`
- Popup styles: `src/extension/popup.css`

### Backend Configuration
For backend customization options, see [src/server/README.md](src/server/README.md).

### Icons
To change the icons, modify `src/extension/assets/mensahe-logo.svg` and run:
```bash
node scripts/generate-icons.mjs
```

## Security Features

- **Passkey Authentication**: Passwordless, phishing-resistant authentication
- **WebAuthn Standard**: Industry-standard security protocol
- **CORS Protection**: Proper cross-origin request handling
- **Error Handling**: Secure error responses without information leakage

For detailed security implementation, see [src/server/README.md](src/server/README.md).

## License

MIT

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test both backend and frontend functionality
5. Submit a pull request

## Support

For issues and questions:
- Create an issue on GitHub
- Check the backend documentation: [src/server/README.md](src/server/README.md)
- Review the code examples and test files

---

<div align="center">
  <sub style="color: #6c757d; font-size: small;">Made with ❤️ from 🇵🇭</sub>
</div>
