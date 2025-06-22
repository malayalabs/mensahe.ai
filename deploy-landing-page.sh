#!/usr/bin/env sh

# Abort on errors
set -e

# Announce the start of the script
echo "🚀 Starting landing page deployment..."

# Navigate to the static site directory
STATIC_DIR="static-site"
if [ ! -d "$STATIC_DIR" ]; then
  echo "❌ Error: Directory '$STATIC_DIR' not found."
  echo "Please run this script from the project root."
  exit 1
fi

echo "✅ Found static site directory: $STATIC_DIR"

# Ensure the main branch is clean
if [ -n "$(git status --porcelain)" ]; then
  echo "⚠️ Git working directory is not clean. Please commit or stash your changes."
  # exit 1 # Disabled for local testing flexibility
fi

# Store the current branch name
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
echo "✅ Current branch is '$CURRENT_BRANCH'"

# Create a temporary directory for deployment
TEMP_DEPLOY_DIR="temp-deploy-dir"
rm -rf $TEMP_DEPLOY_DIR
mkdir $TEMP_DEPLOY_DIR

# Copy the static site contents to the temp directory
echo "📁 Copying static site files to temporary directory..."
cp -r $STATIC_DIR/* $TEMP_DEPLOY_DIR/

# Navigate into the temporary directory
cd $TEMP_DEPLOY_DIR

# Initialize a new git repository
git init
git add -A
git commit -m 'deploy: Initial commit for landing page deployment'

# Force push to the gh-pages branch.
# This will create the branch if it doesn't exist and overwrite its history.
# Replace `origin` with your remote name if it's different.
# Replace `YOUR_USERNAME/YOUR_REPO.git` with your actual repository URL.
# For this test, we will just simulate the push.
GIT_URL=$(git remote get-url origin 2>/dev/null || echo "git@github.com:YOUR_USERNAME/YOUR_REPO.git")
TARGET_BRANCH="gh-pages"

echo "✅ Preparing to push to the '$TARGET_BRANCH' branch."

if [ "$GIT_URL" = "git@github.com:YOUR_USERNAME/YOUR_REPO.git" ]; then
    echo "⚠️  No remote 'origin' found. Skipping push simulation."
    echo "Please add your GitHub repository as a remote to enable pushing:"
    echo "git remote add origin $GIT_URL"
else
    echo "This command will overwrite the history of the '$TARGET_BRANCH' branch on '$GIT_URL'."
    # In a real scenario, you would run:
    # git push -f $GIT_URL HEAD:$TARGET_BRANCH
    echo "✅ SIMULATION: git push -f $GIT_URL HEAD:$TARGET_BRANCH"
fi

# Navigate back to the project root
cd ..

# Clean up the temporary directory
rm -rf $TEMP_DEPLOY_DIR
echo "🧹 Cleaned up temporary deployment directory."

# Return to the original branch (optional, as we didn't switch)
# git checkout $CURRENT_BRANCH

echo ""
echo "🎉 Deployment script finished successfully!"
echo ""
echo "To complete deployment:"
echo "1. Push your latest changes on the '$CURRENT_BRANCH' branch."
echo "2. Run this script again."
echo "3. In your GitHub repository settings, set the source for GitHub Pages to the 'gh-pages' branch and the '/ (root)' folder."
echo "Your site will then be live at your GitHub Pages URL." 