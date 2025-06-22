#!/bin/bash

# Mensahe Landing Page Deployment Script
# This script helps prepare and deploy the static site to GitHub Pages

set -e

echo "🔐 Mensahe Landing Page Deployment"
echo "=================================="

# Check if we're in the right directory
if [ ! -f "index.html" ]; then
    echo "❌ Error: index.html not found. Please run this script from the static-site directory."
    exit 1
fi

echo "✅ Found landing page files"

# Check if git is available
if ! command -v git &> /dev/null; then
    echo "❌ Error: Git is not installed. Please install Git first."
    exit 1
fi

echo "✅ Git is available"

# Check if we're in a git repository
if [ ! -d ".git" ]; then
    echo "⚠️  Not in a git repository. Initializing..."
    git init
    echo "✅ Git repository initialized"
fi

# Add all files
echo "📁 Adding files to git..."
git add .

# Check if there are changes to commit
if git diff --cached --quiet; then
    echo "ℹ️  No changes to commit"
else
    echo "💾 Committing changes..."
    git commit -m "Update landing page $(date +%Y-%m-%d)"
    echo "✅ Changes committed"
fi

# Check if remote origin exists
if ! git remote get-url origin &> /dev/null; then
    echo "⚠️  No remote origin found."
    echo "Please add your GitHub repository as origin:"
    echo "git remote add origin https://github.com/yourusername/your-repo-name.git"
    echo ""
    echo "Then run: git push -u origin main"
    exit 1
fi

echo "✅ Remote origin found"

# Push to GitHub
echo "🚀 Pushing to GitHub..."
git push origin main
echo "✅ Pushed to GitHub"

echo ""
echo "🎉 Deployment complete!"
echo ""
echo "Next steps:"
echo "1. Go to your GitHub repository"
echo "2. Navigate to Settings > Pages"
echo "3. Under 'Source', select 'Deploy from a branch'"
echo "4. Choose 'main' branch and '/static-site' folder"
echo "5. Click 'Save'"
echo ""
echo "Your site will be available at:"
echo "https://yourusername.github.io/your-repo-name/"
echo ""
echo "For custom domain setup, see README.md" 