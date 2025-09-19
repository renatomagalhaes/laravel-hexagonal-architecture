#!/bin/bash

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}🐳 Building development environment...${NC}"

# Construir container de desenvolvimento
echo -e "${YELLOW}📦 Building development container...${NC}"
docker-compose -f docker-compose.yml build --no-cache app

echo -e "${GREEN}✅ Development build completed!${NC}"
echo -e "${YELLOW}🚀 Run 'make up' to start the environment${NC}"
