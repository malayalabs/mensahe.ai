# mensahe.ai

<div align="center">
  <img src="src/extension/assets/mensahe-logo.svg" alt="Mensahe Logo" width="120" height="120">
  <h1>Mensahe Chat Widget</h1>
  <p><strong>A secure peer-to-peer messaging system.</strong></p>
</div>

Mensahe.ai is a chat widget designed for secure, peer-to-peer communication, delivered as a lightweight and installable browser extension.

## Features

- 🎨 **Modern UI**: A clean, modern interface.
- 🔧 **Chrome Extension**: Install as a browser extension for easy access.
- ⚡ **Fast & Lightweight**: Built with vanilla JavaScript for optimal performance.
-  Nostalgic **Yahoo! Messenger** and **Windows XP** themes!

## Quick Start

### Development

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

- Click the extension icon in the toolbar to open the popup.
- Click "Toggle Widget on this Page" to show or hide the launcher icon.
- Click the launcher icon on any webpage to open the chat widget.
- Click outside the widget or use the close button to dismiss it.

## Project Structure

```
mensahe.ai/
├── src/
│   └── extension/            # All extension source files
│       ├── assets/
│       │   ├── icons/        # PNG icons (16, 24, 32, 48, 64, 128, 256px)
│       │   └── mensahe-logo.svg # Main SVG logo
│       ├── manifest.json     # Extension manifest
│       ├── popup.html        # Extension popup UI
│       ├── popup.js          # Popup functionality
│       ├── popup.css         # Popup styles
│       ├── content.js        # Content script (injects widget)
│       ├── content.css       # Widget styles
│       └── background.js     # Background service worker
├── scripts/
│   └── generate-icons.mjs    # Icon generation script
├── vite.extension.config.js  # Extension build configuration
└── package.json              # Dependencies and scripts
```

## Customization

The widget is styled with plain CSS. You can customize:
- Widget styles in `src/extension/content.css`
- Popup styles in `src/extension/popup.css`

To change the icons, modify `src/extension/assets/mensahe-logo.svg` and then run the generation script:
```bash
node scripts/generate-icons.mjs
```

## License

MIT

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## Support

For issues and questions:
- Create an issue on GitHub
- Check the documentation
- Review the code examples 

---

<div align="center">
  <sub style="color: #6c757d; font-size: small;">Made with ❤️ from 🇵🇭</sub>
</div>
