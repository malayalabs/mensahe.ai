#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "🧪 Running PHPUnit Tests..."
echo "================================"

# Run PHPUnit and capture output
output=$(./vendor/bin/phpunit 2>&1)
exit_code=$?

# Extract test results using grep
total_tests=$(echo "$output" | grep -o "Tests: [0-9]*" | grep -o "[0-9]*" | head -1)
failures=$(echo "$output" | grep -o "Failures: [0-9]*" | grep -o "[0-9]*" | head -1)
errors=$(echo "$output" | grep -o "Errors: [0-9]*" | grep -o "[0-9]*" | head -1)
skipped=$(echo "$output" | grep -o "Skipped: [0-9]*" | grep -o "[0-9]*" | head -1)

# Set defaults if not found
total_tests=${total_tests:-0}
failures=${failures:-0}
errors=${errors:-0}
skipped=${skipped:-0}

# Calculate passing tests
passing=$((total_tests - failures - errors - skipped))

# Display progress bar
echo -n "Progress: ["
for ((i=0; i<passing; i++)); do
    echo -n "█"
done
for ((i=0; i<failures+errors; i++)); do
    echo -n "░"
done
echo "]"

# Display test counts
echo -e "${GREEN}✓ Passing: $passing${NC}"
echo -e "${RED}✗ Failing: $((failures + errors))${NC}"
if [ "$skipped" -gt 0 ]; then
    echo -e "${YELLOW}⚠ Skipped: $skipped${NC}"
fi
echo -e "📊 Total: $total_tests"
echo "================================"

# Display full PHPUnit output
echo "$output"

# Exit with PHPUnit's exit code
exit $exit_code 