#!/usr/bin/env sh

# Abort on errors
set -e

# Announce the start of the script
echo "🚀 Starting landing page deployment..."

# This script is designed to be run from the `static-site` directory.
if [ ! -f "index.html" ]; then
  echo "❌ Error: 'index.html' not found."
  echo "Please run this script from within the 'static-site' directory."
  exit 1
fi

echo "✅ Script is running from the correct directory."

# Navigate to the project root to perform git operations
cd ..

# Ensure the main branch is clean
if [ -n "$(git status --porcelain)" ]; then
  echo "⚠️ Git working directory is not clean. Please commit or stash your changes before deploying."
  # exit 1 # Disabled for local testing flexibility
fi

# Store the current branch name and remote URL before changing directories
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
GIT_URL=$(git remote get-url origin 2>/dev/null || echo "git@github.com:YOUR_USERNAME/YOUR_REPO.git")
echo "✅ Current branch is '$CURRENT_BRANCH'"

# Use a temporary directory in the project root for deployment
TEMP_DEPLOY_DIR="temp-deploy-dir"
rm -rf $TEMP_DEPLOY_DIR
mkdir $TEMP_DEPLOY_DIR

# Copy the static site contents to the temp directory
echo "📁 Copying static site files to temporary directory..."
cp -r static-site/* $TEMP_DEPLOY_DIR/

# Navigate into the temporary directory
cd $TEMP_DEPLOY_DIR

# Initialize a new git repository
git init
git add -A
git commit -m 'deploy: Prepare landing page for deployment'

# Deploy to the gh-pages branch
TARGET_BRANCH="gh-pages"
echo "✅ Preparing to push to the '$TARGET_BRANCH' branch."

if [ "$GIT_URL" = "git@github.com:YOUR_USERNAME/YOUR_REPO.git" ]; then
    echo "⚠️  No remote 'origin' found. Skipping push."
    echo "To enable deployment, please add your GitHub repository as a remote:"
    echo "git remote add origin $GIT_URL"
else
    echo "This command will overwrite the history of the '$TARGET_BRANCH' branch on '$GIT_URL'."
    # In a real scenario, you would uncomment the following line:
    # git push -f $GIT_URL HEAD:$TARGET_BRANCH
    echo "✅ SIMULATION: git push -f $GIT_URL HEAD:$TARGET_BRANCH"
fi

# Navigate back to the project root
cd ..

# Clean up the temporary directory
rm -rf $TEMP_DEPLOY_DIR
echo "🧹 Cleaned up temporary deployment directory."

echo ""
echo "🎉 Deployment script finished successfully!"
echo ""
echo "To complete deployment:"
echo "1. Push your latest changes on the '$CURRENT_BRANCH' branch."
echo "2. Set up your 'origin' remote if you haven't already."
echo "3. Uncomment the 'git push' command in the script to enable live deployment."
echo "4. In your GitHub repository settings, set the source for GitHub Pages to the 'gh-pages' branch."
echo "Your site will then be live at your GitHub Pages URL." 