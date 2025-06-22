# Mensahe Landing Page

This is the static landing page for Mensahe, a next-generation instant messaging platform. The site is designed to be deployed on GitHub Pages.

## Features

- **Marketing-focused landing page** with nostalgic 90s-2000s messaging vibes
- **Waitlist collection** for early access signups
- **Responsive design** that works on all devices
- **SEO optimized** with proper meta tags
- **Simple and clean UI** matching the app's design system

## Deployment to GitHub Pages

### Option 1: Automatic Deployment (Recommended)

1. Push this `static-site` folder to your GitHub repository
2. Go to your repository settings on GitHub
3. Navigate to "Pages" in the left sidebar
4. Under "Source", select "Deploy from a branch"
5. Choose the branch (usually `main` or `master`)
6. Set the folder to `/static-site`
7. Click "Save"

Your site will be available at `https://yourusername.github.io/your-repo-name/`

### Option 2: Manual Deployment

1. Copy the contents of this folder to a new repository
2. Follow the same GitHub Pages setup as above
3. The site will be available at `https://yourusername.github.io/your-repo-name/`

## Custom Domain (Optional)

To use a custom domain like `mensahe.ai`:

1. Purchase your domain
2. In GitHub Pages settings, enter your custom domain
3. Add a `CNAME` file in this folder with your domain name
4. Configure DNS settings with your domain provider

## File Structure

```
static-site/
├── index.html          # Main landing page
├── mensahe-logo.svg    # App logo from extension
├── README.md           # This file
└── CNAME              # Custom domain (if using)
```

## Customization

### Colors and Branding
The site uses a consistent color scheme defined in the CSS:
- Primary Blue: `#4299e1`
- Dark Blue: `#3182ce`
- Text Dark: `#1a202c`
- Text Medium: `#4a5568`
- Background Light: `#f8fafc`

### Content Updates
To update content:
1. Edit the HTML directly in `index.html`
2. The site uses inline CSS for simplicity
3. Test locally by opening `index.html` in a browser

### Waitlist Integration
Currently, the waitlist form stores emails in localStorage for demo purposes. To integrate with a real backend:

1. Replace the JavaScript form submission logic
2. Point to your actual API endpoint
3. Handle success/error responses appropriately

## Local Development

To test the site locally:

```bash
# Navigate to the static-site directory
cd static-site

# Open in browser (macOS)
open index.html

# Or serve with a local server
python3 -m http.server 8000
# Then visit http://localhost:8000
```

## SEO and Performance

The site includes:
- Proper meta tags for social sharing
- Semantic HTML structure
- Optimized for mobile devices
- Fast loading with minimal dependencies
- Clean, accessible design

## Analytics (Optional)

To add analytics, you can include Google Analytics or other tracking scripts in the `<head>` section of `index.html`.

## Support

For questions about the landing page or deployment, refer to the main project documentation or create an issue in the repository. 