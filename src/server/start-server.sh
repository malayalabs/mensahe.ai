#!/bin/bash

APP_HOST="${APP_HOST:-localhost}"
APP_PORT="${APP_PORT:-8080}"

echo "🚀 Starting Mensahe PHP Backend Server..."
echo "📍 Server will be available at: http://$APP_HOST:$APP_PORT"
echo "🧪 Test page available at: http://$APP_HOST:$APP_PORT/test-frontend.html"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Start the PHP development server
php -S "$APP_HOST:$APP_PORT" 