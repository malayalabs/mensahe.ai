#!/bin/bash

echo "🚀 Starting Mensahe PHP Backend Server..."
echo "📍 Server will be available at: http://localhost:8080"
echo "🧪 Test page available at: http://localhost:8080/test-frontend.html"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Change to the server directory and start the PHP server
cd src/server
php -S localhost:8080 